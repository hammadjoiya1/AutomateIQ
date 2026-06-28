<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioGenerationService
{
    /**
     * Generate a voiceover MP3 from pre-extracted dialogue with tone-aware voice selection.
     *
     * @param string      $dialogue  The spoken text to synthesize
     * @param int         $projectId The video project ID (for file storage)
     * @param string      $voice     OpenAI voice name (alloy, echo, fable, onyx, nova, shimmer)
     * @param float       $speed     Speech speed (0.25 to 4.0, default 1.0)
     * @return string|null The relative public URL to the audio file, or null on failure
     */
    public function generateVoiceover(string $dialogue, int $projectId, string $voice = 'alloy', float $speed = 1.0): ?string
    {
        $dialogue = trim($dialogue);
        if (empty($dialogue)) {
            return null;
        }

        $apiKey = config('services.openai.api_key') ?? config('openai.api_key') ?? env('OPENAI_API_KEY');
        if (!$apiKey) {
            Log::warning('AudioGenerationService: No OpenAI API Key found for TTS.');
            return null;
        }

        // Clamp speed to valid range
        $speed = max(0.25, min(4.0, $speed));

        // Validate voice
        $validVoices = ['alloy', 'echo', 'fable', 'onyx', 'nova', 'shimmer'];
        if (!in_array($voice, $validVoices)) {
            $voice = 'alloy';
        }

        Log::info("AudioGenerationService: Generating voiceover", [
            'project_id' => $projectId,
            'voice' => $voice,
            'speed' => $speed,
            'text_length' => strlen($dialogue),
            'text_preview' => Str::limit($dialogue, 80),
        ]);

        try {
            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->post('https://api.openai.com/v1/audio/speech', [
                    'model' => 'tts-1-hd',
                    'input' => $dialogue,
                    'voice' => $voice,
                    'speed' => $speed,
                    'response_format' => 'mp3',
                ]);

            if ($response->successful()) {
                $audioContent = $response->body();

                $filename = 'audio_' . Str::random(10) . '.mp3';
                $path = "projects/{$projectId}/audio/{$filename}";

                Storage::disk('public')->put($path, $audioContent);

                Log::info("AudioGenerationService: Audio saved", [
                    'path' => $path,
                    'size_bytes' => strlen($audioContent),
                ]);

                return '/storage/' . $path;
            }

            Log::error('AudioGenerationService: OpenAI TTS Failed', [
                'status' => $response->status(),
                'body' => Str::limit($response->body(), 500),
            ]);
            return null;

        } catch (\Exception $e) {
            Log::error('AudioGenerationService: Exception', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Legacy method: Extract dialogue from raw script text and generate voiceover.
     * Used for backward compatibility with simple mode.
     */
    public function generateFromRawScript(string $scriptText, int $projectId): ?string
    {
        // Extract text inside double quotes
        preg_match_all('/"([^"]+)"/', $scriptText, $matches);

        $dialogue = [];
        if (!empty($matches[1])) {
            $dialogue = $matches[1];
        }

        // Also check for smart quotes
        preg_match_all('/\x{201C}([^\x{201D}]+)\x{201D}/u', $scriptText, $smartMatches);
        if (!empty($smartMatches[1])) {
            $dialogue = array_merge($dialogue, $smartMatches[1]);
        }

        if (empty($dialogue)) {
            return null;
        }

        $textToSpeak = trim(implode(' ', $dialogue));
        if (empty($textToSpeak)) {
            return null;
        }

        return $this->generateVoiceover($textToSpeak, $projectId);
    }
}
