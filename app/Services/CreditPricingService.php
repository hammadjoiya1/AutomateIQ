<?php

namespace App\Services;

use App\Models\Tool;
use App\Models\Setting;

class CreditPricingService
{
    public function creditsForCostCents(int $costCents): int
    {
        $creditPrice = max(1, (int) $this->settingValue('credits.credit_price_cents', config('credits.credit_price_cents', 10)));
        $margin = (float) $this->settingValue('credits.target_gross_margin', config('credits.target_gross_margin', 0.70));
        $margin = max(0.0, min(0.95, $margin));

        $requiredRevenueCents = (int) ceil($costCents / max(0.01, (1 - $margin)));

        return (int) max(1, (int) ceil($requiredRevenueCents / $creditPrice));
    }

    public function openAIModelBlendCentsPer1k(string $model): float
    {
        $settingKey = "credits.openai_models.{$model}.blended_cents_per_1k";
        $settingValue = $this->settingValue($settingKey, null);
        if ($settingValue !== null && $settingValue !== '') {
            return (float) $settingValue;
        }

        $models = config('credits.openai_models', []);
        if (isset($models[$model]['blended_cents_per_1k'])) {
            return (float) $models[$model]['blended_cents_per_1k'];
        }

        $fallback = config('services.openai.model', 'gpt-4');
        return (float) ($models[$fallback]['blended_cents_per_1k'] ?? 6.00);
    }

    public function estimateOpenAICostCents(string $model, int $inputTokens, int $outputTokens): int
    {
        $totalTokens = max(0, $inputTokens) + max(0, $outputTokens);
        return $this->estimateOpenAICostCentsFromTotalTokens($model, $totalTokens);
    }

    public function estimateOpenAICostCentsFromTotalTokens(string $model, int $totalTokens): int
    {
        $ratePer1k = $this->openAIModelBlendCentsPer1k($model);
        return (int) ceil(($totalTokens / 1000) * $ratePer1k);
    }

    public function estimateVideoCostCents(string $modelName, int $numFrames, int $fps): int
    {
        $settingKey = "credits.replicate_models.{$modelName}.cents_per_second";
        $settingRate = $this->settingValue($settingKey, null);
        if ($settingRate !== null && $settingRate !== '') {
            $rate = (float) $settingRate;
        } else {
            $models = config('credits.replicate_models', []);
            $rate = (float) ($models[$modelName]['cents_per_second'] ?? 6.00);
        }
        $durationSeconds = $this->estimateVideoSeconds($numFrames, $fps);

        return (int) ceil($durationSeconds * $rate);
    }

    public function estimateTextCreditsFromTokens(int $inputTokens, int $outputTokens, ?string $model = null): int
    {
        $model = $model ?: config('services.openai.model', 'gpt-4');
        $costCents = $this->estimateOpenAICostCents($model, $inputTokens, $outputTokens);

        return $this->creditsForCostCents($costCents);
    }

    public function estimateVideoRunCredits(): int
    {
        $defaults = $this->getVideoDefaults();
        $numFrames = (int) ($defaults['num_frames'] ?? 16);
        $fps = (int) ($defaults['fps'] ?? 8);
        $replicateModels = config('credits.replicate_models', []);
        $modelName = (string) (array_key_first($replicateModels) ?? 'cjwbw/damo-text-to-video');

        $costCents = $this->estimateVideoCostCents($modelName, $numFrames, $fps);

        return $this->creditsForCostCents($costCents);
    }

    public function estimateToolCredits(Tool $tool): int
    {
        if ($tool->tool_type === 'video') {
            $credits = $this->estimateVideoRunCredits();
        } else {
            $inputTokens = max(200, $this->estimateTokensFromText($tool->description ?? ''));
            $outputTokens = $this->estimateToolMaxOutputTokens($tool);
            $credits = $this->estimateTextCreditsFromTokens($inputTokens, $outputTokens);
        }

        return max($credits, (int) ($tool->cost_credits ?? 0));
    }

    public function estimateVideoSeconds(int $numFrames, int $fps): float
    {
        $fps = max(1, $fps);
        return $numFrames / $fps;
    }

    public function getVideoDefaults(): array
    {
        $defaults = config('credits.video_defaults', ['num_frames' => 16, 'fps' => 8]);
        $numFrames = (int) $this->settingValue('credits.video_defaults.num_frames', $defaults['num_frames'] ?? 16);
        $fps = (int) $this->settingValue('credits.video_defaults.fps', $defaults['fps'] ?? 8);

        return [
            'num_frames' => $numFrames,
            'fps' => $fps,
        ];
    }

    public function estimateTokensFromText(string $text): int
    {
        $chars = strlen($text);
        return (int) ceil($chars / 4);
    }

    public function estimateToolMaxOutputTokens(Tool $tool): int
    {
        $slug = $tool->slug;

        if (str_contains($slug, 'long')) {
            return 2500;
        }
        if (str_contains($slug, 'script')) {
            return 1500;
        }
        if (str_contains($slug, 'description')) {
            return 1000;
        }

        return 800;
    }

    protected function settingValue(string $key, mixed $default): mixed
    {
        $value = Setting::get($key, null);

        if ($value === null || $value === '') {
            return $default;
        }

        return $value;
    }
}
