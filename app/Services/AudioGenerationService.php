<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioGenerationService
{
    /**
     * Extracts spoken dialogue (text in quotes) from a script line
     * and generates an MP3 voiceover using OpenAI TTS.
     * 
     * @param string $scriptText The script line for the scene
     * @param int $projectId The video project ID
     * @return string|null The relative public URL to the audio file, or null if no dialogue
     */
    public function generateVoiceover(string $scriptText, int $projectId): ?string
    {
        // Extract text inside double quotes
        preg_match_all('/"([^"]+)"/', $scriptText, $matches);
        
        $dialogue = [];
        if (!empty($matches[1])) {
            $dialogue = $matches[1];
        }

        // Also check for smart quotes if normal quotes aren't used
        preg_match_all('/“([^”]+)”/', $scriptText, $smartMatches);
        if (!empty($smartMatches[1])) {
            $dialogue = array_merge($dialogue, $smartMatches[1]);
        }

        if (empty($dialogue)) {
            return null; // No spoken dialogue in this scene
        }

        $textToSpeak = implode(" ", $dialogue);
        
        // Ensure text is clean
        $textToSpeak = trim($textToSpeak);
        if (empty($textToSpeak)) {
            return null;
        }

        $apiKey = env('FACELESS_OPENAI_KEY') ?? env('OPENAI_API_KEY');
        if (!$apiKey) {
            \Illuminate\Support\Facades\Log::warning('No OpenAI API Key found for TTS.');
            return null;
        }

        // Call OpenAI TTS API
        $response = Http::withToken($apiKey)
            ->timeout(60)
            ->post('https://api.openai.com/v1/audio/speech', [
                'model' => 'tts-1',
                'input' => $textToSpeak,
                'voice' => 'alloy', // Options: alloy, echo, fable, onyx, nova, shimmer
                'response_format' => 'mp3',
            ]);

        if ($response->successful()) {
            $audioContent = $response->body();
            
            // Save to storage
            $filename = 'audio_' . Str::random(10) . '.mp3';
            $path = "projects/{$projectId}/audio/{$filename}";
            
            Storage::disk('public')->put($path, $audioContent);
            
            return '/storage/' . $path;
        }

        \Illuminate\Support\Facades\Log::error('OpenAI TTS Failed: ' . $response->body());
        return null;
    }
}
