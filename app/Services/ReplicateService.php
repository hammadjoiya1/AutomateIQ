<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReplicateService
{
    protected $baseUrl = 'https://api.replicate.com/v1';
    protected $token;

    public function __construct()
    {
        $this->token = config('services.replicate.api_token');
    }

    public function generateVideo($prompt, string $model = 'minimax/video-01')
    {
        if (!$this->token) {
            throw new \Exception('Replicate API Token is missing.');
        }

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/models/{$model}/predictions", [
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
