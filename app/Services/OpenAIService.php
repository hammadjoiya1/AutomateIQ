<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use Exception;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    /**
     * Generate a video script using OpenAI API
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function generateScript(array $params): array
    {
        try {
            $prompt = $this->buildScriptPrompt($params);

            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional script writer specializing in faceless video content. Create engaging, well-structured scripts with clear hooks, body content, and calls to action.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => $this->getMaxTokens($params['length'] ?? 'medium'),
                'temperature' => 0.7,
            ]);

            $scriptContent = $response->choices[0]->message->content;

            return [
                'success' => true,
                'script' => $scriptContent,
                'tokens_used' => $response->usage->totalTokens ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('OpenAI Script Generation Error: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => 'Failed to generate script. Please try again.',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build the prompt for script generation
     *
     * @param array $params
     * @return string
     */
    protected function buildScriptPrompt(array $params): string
    {
        $topic = $params['topic'];
        $tone = $params['tone'] ?? 'professional';
        $length = $params['length'] ?? 'medium';
        $duration = $this->getDuration($length);
        $audience = $params['target_audience'] ?? 'general audience';
        $keyPoints = $params['key_points'] ?? '';

        $prompt = "Create a {$length} video script about: {$topic}\n\n";
        $prompt .= "**Requirements:**\n";
        $prompt .= "- Tone: {$tone}\n";
        $prompt .= "- Target Duration: ~{$duration} seconds\n";
        $prompt .= "- Target Audience: {$audience}\n";

        if ($keyPoints) {
            $prompt .= "- Key Points to Include: {$keyPoints}\n";
        }

        $prompt .= "\n**Script Structure:**\n";
        $prompt .= "1. **Hook** (First 5 seconds) - Grab attention immediately\n";
        $prompt .= "2. **Introduction** (10-15 seconds) - Set context\n";
        $prompt .= "3. **Main Content** (3-5 key points with explanations)\n";
        $prompt .= "4. **Call to Action** (5-10 seconds)\n";
        $prompt .= "5. **Outro** (Final 5 seconds)\n\n";
        $prompt .= "Format with clear section headers. Make it engaging and suitable for faceless video content.";

        return $prompt;
    }

    /**
     * Get max tokens based on script length
     *
     * @param string $length
     * @return int
     */
    protected function getMaxTokens(string $length): int
    {
        return match ($length) {
            'short' => 800,
            'medium' => 1500,
            'long' => 2500,
            default => 1500,
        };
    }

    protected function getDuration(string $length): int
    {
        return match ($length) {
            'short' => 30,
            'medium' => 60,
            'long' => 180,
            default => 60,
        };
    }

    /**
     * Enhance a short video prompt into a highly detailed cinematic prompt.
     *
     * @param string $prompt
     * @return string
     * @throws Exception
     */
    public function enhancePrompt(string $prompt): string
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert AI Video Prompt Engineer. Your job is to take a short, basic description of a video scene and expand it into a highly detailed, cinematic, and descriptive prompt optimized for AI video generators (like Sora, MiniMax, or Runway). 
                        Focus on: lighting, camera angle, subject details, atmosphere, motion, and lens type (e.g., 35mm, cinematic depth of field). 
                        Do NOT output any conversational filler or introductions. Just output the raw enhanced prompt.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Enhance this video prompt: {$prompt}"
                    ]
                ],
                'max_tokens' => 300,
                'temperature' => 0.7,
            ]);

            return trim($response->choices[0]->message->content);

        } catch (Exception $e) {
            Log::error('OpenAI Prompt Enhancement Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate an SEO-optimized markdown blog post.
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function generateBlogPost(array $params): array
    {
        try {
            $topic = $params['topic'] ?? 'General topic';
            $tone = $params['tone'] ?? 'professional';
            $keywords = $params['keywords'] ?? '';
            
            $prompt = "Write a comprehensive, SEO-optimized blog post about: {$topic}.\n\n";
            $prompt .= "**Requirements:**\n";
            $prompt .= "- Tone: {$tone}\n";
            if ($keywords) {
                $prompt .= "- Target Keywords: {$keywords}\n";
            }
            $prompt .= "- Format: Return ONLY well-formatted Markdown for the body of the post. Use headers, bullet points, and bold text for structure.\n";
            $prompt .= "- Include a compelling introduction and a strong conclusion.\n";

            $response = OpenAI::chat()->create([
                'model' => config('services.openai.model', 'gpt-4'),
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert SEO copywriter and content marketer. You write engaging, highly-ranked blog posts formatted perfectly in Markdown.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 2500,
                'temperature' => 0.7,
            ]);

            $markdownContent = $response->choices[0]->message->content;
            
            // Strip markdown code block formatting if present
            $markdownContent = preg_replace('/^```markdown\s*|\s*```$/i', '', trim($markdownContent));

            return [
                'success' => true,
                'content' => trim($markdownContent),
                'tokens_used' => $response->usage->totalTokens ?? 0,
            ];

        } catch (Exception $e) {
            Log::error('OpenAI Blog Generation Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
