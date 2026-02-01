<?php

namespace App\Services;

use App\Models\VideoProject;
use HalilCosdu\Replicate\Facades\Replicate;
use Illuminate\Support\Facades\Log;

class VideoGenerationService
{
    /**
     * The Replicate models.
     * Use Damo Text-to-Video (Verified working version hash)
     */
    protected string $modelId = '1e205ea73084bd17a0a3b43396e49ba0d6bc2e754e9283b2df49fad2dcf95755';
    protected string $modelName = 'cjwbw/damo-text-to-video';

    /**
     * Create a video generation prediction via Replicate API.
     *
     * @param VideoProject $project
     * @return string|null The prediction ID if successful
     */
    public function generate(VideoProject $project): ?string
    {
        try {
            // Build the prompt with visual style
            $prompt = $this->buildPrompt($project);

            $versionId = $this->modelId;

            // Damo Input Parameters (Minimal to avoid 422)
            $input = [
                'prompt' => $prompt,
                'num_frames' => 16,
                'fps' => 8,
            ];

            $response = Replicate::createPrediction([
                'version' => $versionId,
                'input' => $input,
            ]);

            $predictionId = $response['id'] ?? null;

            if ($predictionId) {
                $project->update([
                    'status' => 'generating',
                    'settings' => array_merge($project->settings ?? [], [
                        'replicate_prediction_id' => $predictionId,
                        'model_version' => $versionId,
                        'used_model' => $this->modelName,
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

            $prompt = $this->buildScenePrompt($scene, $project);
            $versionId = $this->modelId;

            // Damo Input Parameters
            $input = [
                'prompt' => $prompt,
                'num_frames' => 16,
                'fps' => 8,
            ];

            $response = Replicate::createPrediction([
                'version' => $versionId,
                'input' => $input,
            ]);

            $predictionId = $response['id'] ?? null;

            if ($predictionId) {
                $scene->update([
                    'status' => 'generating',
                    'replicate_prediction_id' => $predictionId,
                    'settings' => array_merge($scene->settings ?? [], [
                        'model_version' => $versionId,
                        'used_model' => $this->modelName,
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
            $response = Replicate::getPrediction($predictionId);

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
            $response = Replicate::getPrediction($predictionId);
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
