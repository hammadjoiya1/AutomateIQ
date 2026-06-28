<?php

namespace App\Services;

use App\Models\VideoProject;
use HalilCosdu\Replicate\Facades\Replicate;
use Illuminate\Support\Facades\Log;

class VideoGenerationService
{
    /**
     * Resolve the Replicate model name from the quality tier.
     */
    protected function resolveModel(string $quality): string
    {
        $tiers = config('credits.video_tiers', []);

        if (isset($tiers[$quality]['model'])) {
            return $tiers[$quality]['model'];
        }

        // Fallback to HD tier or hardcoded default
        return $tiers['hd']['model'] ?? 'minimax/video-01';
    }

    /**
     * Create a video generation prediction via Replicate API.
     *
     * @param VideoProject $project
     * @return string|null The prediction ID if successful
     */
    public function generate(VideoProject $project): ?string
    {
        try {
            $quality = $project->settings['quality'] ?? 'hd';
            $modelName = $this->resolveModel($quality);

            // Build the prompt with visual style
            $prompt = $this->buildPrompt($project);

            $replicateService = app(ReplicateService::class);
            $response = $replicateService->generateVideo($prompt, $modelName);

            $predictionId = $response['id'] ?? null;

            if ($predictionId) {
                $project->update([
                    'status'   => 'generating',
                    'settings' => array_merge($project->settings ?? [], [
                        'replicate_prediction_id' => $predictionId,
                        'used_model'              => $modelName,
                    ]),
                ]);
            }

            return $predictionId;

        } catch (\Exception $e) {
            Log::error('Video generation failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);

            $project->update(['status' => 'failed']);

            return null;
        }
    }

    public function generateScene(\App\Models\VideoScene $scene): ?string
    {
        try {
            $project = $scene->project;
            $quality = $project->settings['quality'] ?? 'hd';
            $modelName = $this->resolveModel($quality);

            $prompt = $this->buildScenePrompt($scene, $project);
            $replicateService = app(ReplicateService::class);
            $response = $replicateService->generateVideo($prompt, $modelName);

            $predictionId = $response['id'] ?? null;

            if ($predictionId) {
                // Generate audio track using pre-parsed dialogue and tone
                $audioUrl = null;
                $settings = $scene->settings ?? [];
                $dialogue = $settings['dialogue'] ?? '';
                
                if (!empty($dialogue)) {
                    $voice = $settings['voice'] ?? 'alloy';
                    $speed = $settings['speed'] ?? 1.0;
                    $audioUrl = app(\App\Services\AudioGenerationService::class)
                        ->generateVoiceover($dialogue, $project->id, $voice, $speed);
                }

                $scene->update([
                    'status'                  => 'generating',
                    'replicate_prediction_id' => $predictionId,
                    'audio_url'               => $audioUrl,
                    'settings'                => array_merge($settings, [
                        'used_model' => $modelName,
                    ]),
                ]);
            }

            return $predictionId;

        } catch (\Exception $e) {
            Log::error('Scene generation failed', [
                'scene_id' => $scene->id,
                'error' => $e->getMessage(),
            ]);

            $scene->update(['status' => 'failed']);

            return null;
        }
    }

    public function checkStatus(VideoProject $project): array
    {
        $predictionId = $project->settings['replicate_prediction_id'] ?? null;

        if (!$predictionId) {
            return ['status' => 'failed', 'error' => 'No prediction ID found'];
        }

        try {
            $replicateService = app(ReplicateService::class);
            $response = $replicateService->checkStatus($predictionId);

            $status = $response['status'] ?? 'unknown';
            $output = $response['output'] ?? null;
            $error = $response['error'] ?? null;
            $logs = $response['logs'] ?? '';

            if ($status === 'succeeded' && $output) {
                $videoUrl = is_array($output) ? $output[0] : $output;
                $project->update([
                    'status' => 'completed',
                    'video_url' => $videoUrl,
                    'completed_at' => now(),
                ]);
            } elseif ($status === 'failed' || $status === 'canceled') {
                $project->update(['status' => 'failed']);
            }

            return [
                'status' => $status,
                'output' => $output,
                'error' => $error,
                'logs' => $logs,
            ];

        } catch (\Exception $e) {
            Log::error('Failed to check video status', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }

    public function checkSceneStatus(\App\Models\VideoScene $scene): array
    {
        $predictionId = $scene->replicate_prediction_id;

        if (!$predictionId)
            return ['status' => 'failed', 'error' => 'No ID'];

        try {
            $replicateService = app(ReplicateService::class);
            $response = $replicateService->checkStatus($predictionId);
            $status = $response['status'] ?? 'unknown';
            $output = $response['output'] ?? null;

            if ($status === 'succeeded' && $output) {
                $videoUrl = is_array($output) ? $output[0] : $output;
                $scene->update(['status' => 'completed', 'video_url' => $videoUrl]);
            } elseif ($status === 'failed' || $status === 'canceled') {
                $scene->update(['status' => 'failed']);
            }

            return ['status' => $status, 'output' => $output];
        } catch (\Exception $e) {
            return ['status' => 'error', 'error' => $e->getMessage()];
        }
    }

    protected function buildPrompt(VideoProject $project): string
    {
        $styleModifiers = [
            'realistic' => 'photorealistic, highly detailed, cinematic lighting',
            'cinematic' => 'cinematic, film grain, movie quality, dramatic lighting',
            'anime' => 'anime style, japanese animation, vibrant colors',
            'cyberpunk' => 'cyberpunk aesthetic, neon lights, futuristic city',
            'watercolor' => 'watercolor painting style, soft colors, artistic',
            'pixel' => 'pixel art style, 8-bit graphics, retro game',
            'horror' => 'dark horror atmosphere, eerie lighting, unsettling',
            'painting' => 'oil painting style, classical art, rich textures',
            'comic' => 'comic book style, bold outlines, vibrant panels',
            'vintage' => 'vintage film look, sepia tones, old movie aesthetic',
        ];

        $style = $project->visual_style ?? 'realistic';
        $modifier = $styleModifiers[$style] ?? $styleModifiers['realistic'];

        return "{$project->prompt}, {$modifier}";
    }

    protected function buildScenePrompt(\App\Models\VideoScene $scene, VideoProject $project): string
    {
        $styleModifiers = [
            'realistic' => 'photorealistic, highly detailed, cinematic lighting',
            'cinematic' => 'cinematic, film grain, movie quality, dramatic lighting',
            'anime' => 'anime style, japanese animation, vibrant colors',
            'cyberpunk' => 'cyberpunk aesthetic, neon lights, futuristic city',
            'watercolor' => 'watercolor painting style, soft colors, artistic',
            'pixel' => 'pixel art style, 8-bit graphics, retro game',
            'horror' => 'dark horror atmosphere, eerie lighting, unsettling',
            'painting' => 'oil painting style, classical art, rich textures',
            'comic' => 'comic book style, bold outlines, vibrant panels',
            'vintage' => 'vintage film look, sepia tones, old movie aesthetic',
        ];

        $style = $project->visual_style ?? 'realistic';
        $modifier = $styleModifiers[$style] ?? $styleModifiers['realistic'];

        return "{$scene->image_prompt}, {$modifier}";
    }
}
