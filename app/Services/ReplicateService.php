<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ReplicateService
{
    protected $baseUrl = 'https://api.replicate.com/v1';
    protected $token;

    public function __construct()
    {
        $this->token = config('services.replicate.api_token');
    }

    public function getLatestVersion(string $model)
    {
        return Cache::remember("replicate_model_version_{$model}", 86400, function () use ($model) {
            $response = Http::withToken($this->token)->get("{$this->baseUrl}/models/{$model}/versions");
            if ($response->successful()) {
                $versions = $response->json('results');
                if (!empty($versions)) {
                    return $versions[0]['id'];
                }
            }
            throw new \Exception("Could not fetch version for model {$model}. Replicate response: " . $response->body());
        });
    }

    public function generateVideo($prompt, string $model = 'minimax/video-01')
    {
        if (!$this->token) {
            throw new \Exception('Replicate API Token is missing.');
        }

        $version = $this->getLatestVersion($model);

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/predictions", [
            'version' => $version,
            'input' => [
                'prompt'           => $prompt,
                'prompt_optimizer' => true,
            ],
        ]);

        if ($response->failed()) {
            Log::error('Replicate API Error: ' . $response->body());
            throw new \Exception('Failed to start video generation: ' . ($response->json()['detail'] ?? 'Unknown error'));
        }

        return $response->json();
    }

    public function checkStatus($predictionId)
    {
        $response = Http::withToken($this->token)->get("{$this->baseUrl}/predictions/{$predictionId}");

        if ($response->failed()) {
            throw new \Exception('Failed to check status');
        }

        return $response->json();
    }
}
