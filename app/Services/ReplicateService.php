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

    public function generateVideo($prompt)
    {
        if (!$this->token) {
            throw new \Exception('Replicate API Token is missing.');
        }

        // Using ModelScope Text-to-Video (Damo)
        // Model: cjwbw/damo-text-to-video
        $version = "1e205ea73084bd17a0a3b43396e49ba0d6bc2e754e9283b2df49fad2dcf95755";

        $response = Http::withToken($this->token)->post("{$this->baseUrl}/predictions", [
            'version' => $version,
            'input' => [
                'prompt' => $prompt,
                'num_frames' => 24, // Damo might ignore this but it's safe
                'fps' => 16,
            ],
        ]);

        if ($response->failed()) {
            Log::error('Replicate API Error: ' . $response->body());
            throw new \Exception('Failed to start video generation: ' . $response->json()['detail'] ?? 'Unknown error');
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
