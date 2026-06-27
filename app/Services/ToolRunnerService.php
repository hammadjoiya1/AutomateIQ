<?php

namespace App\Services;

use App\Models\Tool;
use App\Models\ToolRun;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\UsageMeterService;
use App\Services\CreditPricingService;
use App\Jobs\GenerateVideoJob;

class ToolRunnerService
{
    public function run(Tool $tool, array $input, ?int $userId = null)
    {
        $inputText = $input['input'] ?? '';
        $user = $userId ? \App\Models\User::find($userId) : null;
        $startTime = microtime(true);
        $pricing = app(CreditPricingService::class);

        // --- VIDEO GENERATION (Replicate) 🎥 ---
        if ($tool->tool_type === 'video') {
            $quality = $input['quality'] ?? 'hd';
            [$costCents, $creditsRequired] = $this->estimateVideoCosts($pricing, $quality);
            $creditsRequired = max($creditsRequired, (int) ($tool->cost_credits ?? 0));

            $this->enforceBudgetCaps($tool, $user, $creditsRequired);

            if ($user && $user->credits < $creditsRequired) {
                throw new \Exception("Insufficient credits. This tool requires {$creditsRequired} credits, but you have {$user->credits}.");
            }

            try {
                Log::info("Starting Video Generation for User {$userId}");
                $run = ToolRun::create([
                    'user_id' => $userId,
                    'tool_id' => $tool->id,
                    'input_data' => $input,
                    'output_text' => 'VIDEO_GENERATION_QUEUED',
                    'status' => 'queued',
                    'cost_credits' => $creditsRequired,
                    'cost_cents' => $costCents,
                    'credits_charged' => $creditsRequired,
                ]);

                $run->update([
                    'output_text' => 'VIDEO_GENERATION_QUEUED: ' . $run->id,
                ]);

                GenerateVideoJob::dispatchSync($run->id);

                // Deduct credits (Expensive!)
                if ($user) {
                    $user->debitCredits($creditsRequired);
                    app(UsageMeterService::class)->recordToolRun($user, true, $costCents, $creditsRequired);
                }

                return $run;
            } catch (\Throwable $e) {
                Log::error("Video Generation Failed: " . $e->getMessage());
                // Return a special error run so the user sees it
                $run = ToolRun::create([
                    'user_id' => $userId,
                    'tool_id' => $tool->id,
                    'input_data' => $input,
                    'output_text' => "Error: " . $e->getMessage(),
                    'status' => 'failed',
                    'cost_credits' => $creditsRequired,
                    'cost_cents' => $costCents,
                    'credits_charged' => 0,
                ]);
                return $run; // Return the error run instead of crashing
            }
        }

        try {
            // Build prompt
            $prompt = $this->buildPromptForTool($tool, $input);
            $prompt = $this->applySettingsToPrompt($prompt, $input, $tool);
            $systemPrompt = $this->getSystemPrompt($tool);

            [$estimatedCostCents, $estimatedCredits] = $this->estimateOpenAICosts($pricing, $tool, $systemPrompt, $prompt);
            $this->enforceBudgetCaps($tool, $user, $estimatedCredits);

            if ($user && $user->credits < $estimatedCredits) {
                throw new \Exception("Insufficient credits. This tool requires {$estimatedCredits} credits, but you have {$user->credits}.");
            }

            Log::info('=== CALLING OPENAI VIA HTTP ===');

            $result = $this->callOpenAIWithRetry($tool, $systemPrompt, $prompt);
            $output = $result['output'];
            $tokensUsed = $result['tokens_used'];
            $inputTokens = $result['input_tokens'];
            $outputTokens = $result['output_tokens'];
            $modelUsed = $result['model_used'];
            $retryCount = $result['retry_count'];

            // Clean Markdown code blocks if present (common with JSON)
            $output = preg_replace('/^```json\s*|\s*```$/', '', $output);

        } catch (\Throwable $e) {
            Log::error('Tool Run Error: ' . $e->getMessage());
            $output = "Error: " . $e->getMessage();
            $tokensUsed = 0;
            $inputTokens = 0;
            $outputTokens = 0;
            $modelUsed = null;
            $retryCount = 0;
        }

        $durationMs = (int) round((microtime(true) - $startTime) * 1000);
        $status = str_starts_with($output ?? '', 'Error') ? 'failed' : 'completed';

        $costCents = $pricing->estimateOpenAICostCents($modelUsed ?? config('services.openai.model', 'gpt-4'), $inputTokens, $outputTokens);
        $creditsRequired = max(
            $pricing->creditsForCostCents($costCents),
            (int) ($tool->cost_credits ?? 0)
        );

        // Save run
        $run = ToolRun::create([
            'user_id' => $userId,
            'tool_id' => $tool->id,
            'input_data' => $input,
            'output_text' => $output,
            'tokens_used' => $tokensUsed,
            'status' => $status,
            'cost_credits' => $creditsRequired,
            'cost_cents' => $costCents,
            'credits_charged' => $status === 'completed' ? $creditsRequired : 0,
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'duration_ms' => $durationMs,
            'model_used' => $modelUsed,
            'retry_count' => $retryCount,
        ]);

        // 3. Deduct Credits (non-fatal – must not affect the returned output)
        if ($user && isset($output) && !str_starts_with($output, 'Error')) {
            try {
                $user->debitCredits($creditsRequired);
                app(UsageMeterService::class)->recordToolRun($user, false, $costCents, $creditsRequired);
            } catch (\Throwable $e) {
                Log::warning('Usage metering failed (non-fatal): ' . $e->getMessage());
            }
        }

        $this->dispatchWebhooks($run, $user);

        return $run;
    }

    public function startStreamRun(Tool $tool, array $input, ?int $userId = null): ToolRun
    {
        $user = $userId ? \App\Models\User::find($userId) : null;
        $pricing = app(CreditPricingService::class);

        $prompt = $this->buildPromptForTool($tool, $input);
        $prompt = $this->applySettingsToPrompt($prompt, $input, $tool);
        $systemPrompt = $this->getSystemPrompt($tool);

        [$estimatedCostCents, $estimatedCredits] = $this->estimateOpenAICosts($pricing, $tool, $systemPrompt, $prompt);

        $this->enforceBudgetCaps($tool, $user, $estimatedCredits);

        if ($user && $user->credits < $estimatedCredits) {
            throw new \Exception("Insufficient credits. This tool requires {$estimatedCredits} credits, but you have {$user->credits}.");
        }

        return ToolRun::create([
            'user_id' => $userId,
            'tool_id' => $tool->id,
            'input_data' => $input,
            'output_text' => '',
            'status' => 'streaming',
            'cost_credits' => $estimatedCredits,
            'cost_cents' => $estimatedCostCents,
            'credits_charged' => 0,
        ]);
    }

    public function streamToOutput(Tool $tool, array $input, ToolRun $run, callable $onChunk): void
    {
        $startTime = microtime(true);
        $user = $run->user_id ? \App\Models\User::find($run->user_id) : null;
        $pricing = app(CreditPricingService::class);

        try {
            $prompt = $this->buildPromptForTool($tool, $input);
            $prompt = $this->applySettingsToPrompt($prompt, $input, $tool);
            $systemPrompt = $this->getSystemPrompt($tool);

            $result = $this->streamOpenAIWithRetry($tool, $systemPrompt, $prompt, $onChunk);

            $output = $result['output'];
            $modelUsed = $result['model_used'];
            $retryCount = $result['retry_count'];

            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            $inputTokens = $pricing->estimateTokensFromText($systemPrompt) + $pricing->estimateTokensFromText($prompt);
            $outputTokens = $pricing->estimateTokensFromText($output);
            $costCents = $pricing->estimateOpenAICostCents($modelUsed, $inputTokens, $outputTokens);
            $creditsRequired = max(
                $pricing->creditsForCostCents($costCents),
                (int) ($tool->cost_credits ?? 0)
            );

            $run->update([
                'output_text' => $output,
                'status' => 'completed',
                'duration_ms' => $durationMs,
                'model_used' => $modelUsed,
                'retry_count' => $retryCount,
                'input_tokens' => $inputTokens,
                'output_tokens' => $outputTokens,
                'tokens_used' => $inputTokens + $outputTokens,
                'cost_cents' => $costCents,
                'cost_credits' => $creditsRequired,
                'credits_charged' => $creditsRequired,
            ]);

        } catch (\Throwable $e) {
            Log::error('Tool Stream Error: ' . $e->getMessage());
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);

            $run->update([
                'output_text' => 'Error: ' . $e->getMessage(),
                'status' => 'failed',
                'duration_ms' => $durationMs,
            ]);

            $this->dispatchWebhooks($run->refresh(), $user);
            return;
        }

        // Deduct credits & record usage AFTER closing the stream try-catch so
        // a DB hiccup here never overwrites the successfully-streamed output.
        if ($user) {
            try {
                $user->debitCredits((int) $creditsRequired);
                app(UsageMeterService::class)->recordToolRun($user, false, $costCents, $creditsRequired);
            } catch (\Throwable $e) {
                Log::warning('Usage metering failed (non-fatal): ' . $e->getMessage());
            }
        }

        $this->dispatchWebhooks($run->refresh(), $user);
    }

    protected function getSystemPrompt(Tool $tool): string
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'script')) {
            return 'You are a professional script writer for faceless video content.';
        }
        if (str_contains($slug, 'hook')) {
            return 'You are an expert at creating viral video hooks.';
        }
        if (str_contains($slug, 'caption')) {
            return 'You are a social media caption expert.';
        }
        if (str_contains($slug, 'hashtag')) {
            $prompt = 'You are a hashtag strategist.';
        } elseif (str_contains($slug, 'splitter')) {
            $prompt = "You are a specialized Film Director AI.
            Your job is to break a video script into VISUAL SCENES.
            
            IMPORTANT: You must return ONLY valid JSON.
            Structure:
            [
                {
                    \"scene_number\": 1,
                    \"voiceover\": \"(Text from script)\",
                    \"visual_prompt\": \"(Detailed text-to-video prompt for Replicate, describing the scene physically, e.g. 'A drone shot of...')\"
                }
            ]";
        } else {
            $prompt = 'You are an AI content creation assistant.';
        }

        // --- BRAND VOICE INJECTION 🧬 ---
        if ($userId = Auth::id()) {
            $user = \App\Models\User::find($userId);
            if ($voice = $user->active_brand_voice) { // Accessor
                $prompt .= "\n\nIMPORTANT: You must adopt the following Brand Voice/Persona:\n" . $voice->prompt;
                Log::info("Injected Brand Voice: {$voice->name}");
            }
        }

        $prompt .= "\n\nIf any parameters are missing, assume sensible defaults and proceed. Do not ask follow-up questions.";

        return $prompt;
    }

    protected function buildPromptForTool(Tool $tool, array $input): string
    {
        $inputText = $input['input'] ?? '';
        $slug = $tool->slug;

        if (!empty($tool->prompt_template)) {
            $prompt = $this->renderPromptTemplate($tool, $input);
            if (!empty($prompt)) {
                return $this->appendExtraContext($prompt, $input);
            }
        }

        if (str_contains($slug, 'script')) {
            return $this->appendExtraContext("Write a video script about: {$inputText}\n\nInclude: Hook, Introduction, Main points, Call to action, Outro", $input);
        }
        if (str_contains($slug, 'hook')) {
            return $this->appendExtraContext("Create 5 viral video hook variations for: {$inputText}", $input);
        }
        if (str_contains($slug, 'caption')) {
            return $this->appendExtraContext("Write an engaging social media caption for: {$inputText}", $input);
        }
        if (str_contains($slug, 'hashtag')) {
            return $this->appendExtraContext("Generate 20-30 relevant hashtags for: {$inputText}", $input);
        }
        if (str_contains($slug, 'splitter')) {
            return $this->appendExtraContext("Here is the script. Break it into 3-5 second visual scenes for AI Video Generation. Return JSON only.\n\nSCRIPT:\n{$inputText}", $input);
        }

        return $this->appendExtraContext("Create content for: {$inputText}", $input);
    }

    protected function getMaxTokens(Tool $tool): int
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'long'))
            return 2500;
        if (str_contains($slug, 'script'))
            return 1500;
        if (str_contains($slug, 'description'))
            return 1000;

        return 800;
    }

    protected function getToolCost(Tool $tool): int
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'script'))
            return 10;
        if (str_contains($slug, 'hook'))
            return 5;
        if (str_contains($slug, 'caption'))
            return 2;
        if ($tool->tool_type === 'video')
            return 50;

        return 1;
    }

    protected function resolveToolCost(Tool $tool): int
    {
        return $tool->cost_credits ?? $this->getToolCost($tool);
    }

    protected function estimateOpenAICosts(CreditPricingService $pricing, Tool $tool, string $systemPrompt, string $prompt): array
    {
        $inputTokens = $pricing->estimateTokensFromText($systemPrompt) + $pricing->estimateTokensFromText($prompt);
        $outputTokens = $pricing->estimateToolMaxOutputTokens($tool);
        $model = config('services.openai.model', 'gpt-4');
        $costCents = $pricing->estimateOpenAICostCents($model, $inputTokens, $outputTokens);
        $creditsRequired = max(
            $pricing->creditsForCostCents($costCents),
            (int) ($tool->cost_credits ?? 0)
        );

        return [$costCents, $creditsRequired];
    }

    protected function estimateVideoCosts(CreditPricingService $pricing, string $quality = 'hd'): array
    {
        $costCents = $pricing->getVideoTierCostCents($quality);
        $creditsRequired = $pricing->estimateVideoRunCreditsByTier($quality);

        return [$costCents, $creditsRequired];
    }

    protected function enforceBudgetCaps(Tool $tool, ?\App\Models\User $user, int $cost): void
    {
        if (!$user || $user->role === 'admin') {
            return;
        }

        if (!empty($tool->daily_budget_credits)) {
            $todaySpend = ToolRun::where('user_id', $user->id)
                ->where('tool_id', $tool->id)
                ->whereDate('created_at', now())
                ->sum('cost_credits');

            if (($todaySpend + $cost) > $tool->daily_budget_credits) {
                throw new \Exception('You have reached the daily budget cap for this tool.');
            }
        }
    }

    protected function callOpenAIWithRetry(Tool $tool, string $systemPrompt, string $prompt): array
    {
        $primaryModel = config('services.openai.model', 'gpt-4');
        $fallbackModel = config('services.openai.fallback_model');
        $maxRetries = 2;
        $retryCount = 0;

        $response = $this->callOpenAI($primaryModel, $systemPrompt, $prompt, $tool, $retryCount, $maxRetries);

        if (!$response['success'] && $fallbackModel && $fallbackModel !== $primaryModel) {
            $retryCount = 0;
            $response = $this->callOpenAI($fallbackModel, $systemPrompt, $prompt, $tool, $retryCount, 1);
        }

        if (!$response['success']) {
            throw new \Exception($response['error'] ?? 'Unknown error');
        }

        return [
            'output' => $response['output'],
            'tokens_used' => $response['tokens_used'],
            'input_tokens' => $response['input_tokens'],
            'output_tokens' => $response['output_tokens'],
            'model_used' => $response['model_used'],
            'retry_count' => $response['retry_count'],
        ];
    }

    protected function callOpenAI(string $model, string $systemPrompt, string $prompt, Tool $tool, int &$retryCount, int $maxRetries): array
    {
        do {
            try {
                $baseUrl = rtrim(config('openai.base_uri') ?: 'https://api.openai.com/v1', '/');

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('openai.api_key'),
                    'Content-Type' => 'application/json',
                ])
                    ->connectTimeout(10)
                    ->timeout((int) config('openai.request_timeout', 30))
                    ->post("{$baseUrl}/chat/completions", [
                            'model' => $model,
                            'messages' => [
                                ['role' => 'system', 'content' => $systemPrompt],
                                ['role' => 'user', 'content' => $prompt]
                            ],
                            'max_tokens' => $this->getMaxTokens($tool),
                            'temperature' => 0.7,
                        ]);

                $data = $response->json();

                if (isset($data['error'])) {
                    throw new \Exception($data['error']['message'] ?? 'Unknown error');
                }

                if (!isset($data['choices'][0]['message']['content'])) {
                    throw new \Exception('Invalid response structure from OpenAI API');
                }

                return [
                    'success' => true,
                    'output' => $data['choices'][0]['message']['content'],
                    'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                    'input_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                    'output_tokens' => $data['usage']['completion_tokens'] ?? 0,
                    'model_used' => $model,
                    'retry_count' => $retryCount,
                ];
            } catch (\Throwable $e) {
                Log::warning('OpenAI call failed', ['error' => $e->getMessage(), 'model' => $model, 'retry' => $retryCount]);
                if ($retryCount >= $maxRetries) {
                    return [
                        'success' => false,
                        'error' => $e->getMessage(),
                        'model_used' => $model,
                        'retry_count' => $retryCount,
                    ];
                }

                $retryCount++;
                usleep(350000);
            }
        } while ($retryCount <= $maxRetries);

        return [
            'success' => false,
            'error' => 'OpenAI request failed.',
            'model_used' => $model,
            'retry_count' => $retryCount,
        ];
    }

    protected function streamOpenAIWithRetry(Tool $tool, string $systemPrompt, string $prompt, callable $onChunk): array
    {
        $primaryModel = config('services.openai.model', 'gpt-4');
        $fallbackModel = config('services.openai.fallback_model');
        $retryCount = 0;
        $maxRetries = 1;

        $response = $this->streamOpenAI($primaryModel, $systemPrompt, $prompt, $tool, $onChunk, $retryCount);

        while (!$response['success'] && $retryCount < $maxRetries) {
            $retryCount++;
            usleep(350000);
            $response = $this->streamOpenAI($primaryModel, $systemPrompt, $prompt, $tool, $onChunk, $retryCount);
        }

        if (!$response['success'] && $fallbackModel && $fallbackModel !== $primaryModel) {
            $retryCount = 0;
            $response = $this->streamOpenAI($fallbackModel, $systemPrompt, $prompt, $tool, $onChunk, $retryCount);
        }

        if (!$response['success']) {
            throw new \Exception($response['error'] ?? 'Streaming failed');
        }

        return [
            'output' => $response['output'],
            'model_used' => $response['model_used'],
            'retry_count' => $response['retry_count'],
        ];
    }

    protected function streamOpenAI(string $model, string $systemPrompt, string $prompt, Tool $tool, callable $onChunk, int &$retryCount): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('openai.api_key'),
                'Content-Type' => 'application/json',
            ])
                ->connectTimeout(10)
                ->timeout((int) config('openai.request_timeout', 30))
                ->withOptions(['stream' => true])
                ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $model,
                        'stream' => true,
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'max_tokens' => $this->getMaxTokens($tool),
                        'temperature' => 0.7,
                    ]);

            $stream = $response->toPsrResponse()->getBody();
            $buffer = '';
            $output = '';

            while (!$stream->eof()) {
                $buffer .= $stream->read(1024);

                while (($pos = strpos($buffer, "\n")) !== false) {
                    $line = trim(substr($buffer, 0, $pos));
                    $buffer = substr($buffer, $pos + 1);

                    if ($line === '' || !str_starts_with($line, 'data:')) {
                        continue;
                    }

                    $payload = trim(substr($line, 5));

                    if ($payload === '[DONE]') {
                        break 2;
                    }

                    $data = json_decode($payload, true);
                    $chunk = $data['choices'][0]['delta']['content'] ?? '';
                    if ($chunk !== '') {
                        $output .= $chunk;
                        $onChunk($chunk);
                    }
                }
            }

            return [
                'success' => true,
                'output' => $output,
                'model_used' => $model,
                'retry_count' => $retryCount,
            ];
        } catch (\Throwable $e) {
            Log::warning('OpenAI stream failed', ['error' => $e->getMessage(), 'model' => $model]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'model_used' => $model,
                'retry_count' => $retryCount,
            ];
        }
    }

    protected function dispatchWebhooks(ToolRun $run, ?\App\Models\User $user): void
    {
        $toolHook = config('services.webhooks.tool_run');
        $slackHook = config('services.webhooks.slack');

        if (!$toolHook && !$slackHook) {
            return;
        }

        $payload = [
            'event' => 'tool_run.completed',
            'run_id' => $run->id,
            'tool_id' => $run->tool_id,
            'tool_name' => $run->tool->name ?? null,
            'status' => $run->status,
            'cost_credits' => $run->cost_credits,
            'model_used' => $run->model_used,
            'user_id' => $run->user_id,
            'user_email' => $user?->email,
            'created_at' => $run->created_at?->toISOString(),
        ];

        if ($toolHook) {
            try {
                Http::post($toolHook, $payload);
            } catch (\Throwable $e) {
                Log::warning('Tool webhook failed', ['error' => $e->getMessage()]);
            }
        }

        if ($slackHook) {
            try {
                Http::post($slackHook, [
                    'text' => "Tool run: {$payload['tool_name']} ({$payload['status']}) by {$payload['user_email']}",
                ]);
            } catch (\Throwable $e) {
                Log::warning('Slack webhook failed', ['error' => $e->getMessage()]);
            }
        }
    }

    protected function renderPromptTemplate(Tool $tool, array $input): ?string
    {
        if (empty($tool->prompt_template)) {
            return null;
        }

        $prompt = $tool->prompt_template;

        $resolvedInput = $input;

        if (str_contains($prompt, '{{count}}') && empty($resolvedInput['count'])) {
            $resolvedInput['count'] = $this->inferCount($resolvedInput['input'] ?? '') ?? 10;
        }
        if (str_contains($prompt, '{{audience}}') && empty($resolvedInput['audience'])) {
            $resolvedInput['audience'] = 'general audience';
        }
        if (str_contains($prompt, '{{angle}}') && empty($resolvedInput['angle'])) {
            $resolvedInput['angle'] = 'practical/how-to';
        }
        if (str_contains($prompt, '{{series_style}}') && empty($resolvedInput['series_style'])) {
            $resolvedInput['series_style'] = 'short-form';
        }

        foreach ($resolvedInput as $key => $value) {
            $prompt = str_replace('{{' . $key . '}}', (string) $value, $prompt);
        }

        $prompt = preg_replace('/\{\{\s*[^}]+\s*\}\}/', '', $prompt);

        return $prompt;
    }

    protected function inferCount(string $inputText): ?int
    {
        if (preg_match('/\b(\d{1,3})\b/', $inputText, $match)) {
            return (int) $match[1];
        }

        return null;
    }

    protected function appendExtraContext(string $prompt, array $input): string
    {
        $extra = collect($input)
            ->except(['input', 'tone', 'length', 'format'])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value, $key) => ucfirst($key) . ': ' . $value)
            ->values()
            ->all();

        if (!empty($extra)) {
            $prompt .= "\n\nAdditional context:\n" . implode("\n", $extra);
        }

        return $prompt;
    }

    protected function applySettingsToPrompt(string $prompt, array $input, Tool $tool): string
    {
        $tone = $input['tone'] ?? null;
        $length = $input['length'] ?? null;
        $format = $input['format'] ?? null;

        $settings = [];

        if (!empty($tone)) {
            $settings[] = "Tone: {$tone}";
        }
        if (!empty($length)) {
            $settings[] = "Length: {$length}";
        }
        if (!empty($format)) {
            $settings[] = "Format: {$format}";
        }
        if (!empty($tool->output_format)) {
            $settings[] = "Output format: {$tool->output_format}";
        }

        if (!empty($settings)) {
            $prompt .= "\n\n" . implode("\n", $settings);
        }

        return $prompt;
    }
}
