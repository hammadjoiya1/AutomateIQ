<?php

namespace App\Services;

use App\Models\VideoProject;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoStitchingService
{
    public function stitch(VideoProject $project): ?string
    {
        // 1. Get all completed scenes
        $scenes = $project->scenes()->where('status', 'completed')->orderBy('sequence_order')->get();

        if ($scenes->isEmpty()) {
            return null;
        }

        $tempDir = storage_path('app/public/temp/' . $project->id);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $concatListPath = $tempDir . '/concat.txt';
        $concatContent = "";
        $downloadedFiles = [];
        $ffmpeg = $this->getFfmpegPath();

        // 2. Download files locally and mix audio
        foreach ($scenes as $scene) {
            $url = $scene->video_url;
            if (!$url) {
                continue;
            }

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?? 'mp4';
            $rawFilename = "scene_{$scene->sequence_order}_raw.{$extension}";
            $rawLocalPath = $tempDir . '/' . $rawFilename;
            $finalFilename = "scene_{$scene->sequence_order}_final.{$extension}";
            $finalLocalPath = $tempDir . '/' . $finalFilename;

            // Simple download of video
            file_put_contents($rawLocalPath, file_get_contents($url));
            $downloadedFiles[] = $rawLocalPath;
            $downloadedFiles[] = $finalLocalPath; // mark for cleanup

            if ($scene->audio_url) {
                // Download audio
                $audioExtension = pathinfo(parse_url($scene->audio_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?? 'mp3';
                $audioFilename = "scene_{$scene->sequence_order}_audio.{$audioExtension}";
                $audioLocalPath = $tempDir . '/' . $audioFilename;
                
                // Audio URL is relative, e.g., /storage/projects/...
                // We need the absolute path to the file
                $absoluteAudioPath = public_path($scene->audio_url);
                if (!file_exists($absoluteAudioPath)) {
                    $absoluteAudioPath = storage_path('app/public/' . str_replace('/storage/', '', $scene->audio_url));
                }

                // Mix video + audio
                // Removed -shortest so that if the audio is 1 sec, the video still plays for its full 5 secs.
                // If audio is longer than video, the video will freeze on the last frame while audio finishes.
                $cmd = "{$ffmpeg} -y -i \"{$rawLocalPath}\" -i \"{$absoluteAudioPath}\" -c:v copy -c:a aac -map 0:v:0 -map 1:a:0 \"{$finalLocalPath}\"";
                \Illuminate\Support\Facades\Process::run($cmd);
            } else {
                // Mix video + silent audio
                $cmd = "{$ffmpeg} -y -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -i \"{$rawLocalPath}\" -c:v copy -c:a aac -map 1:v:0 -map 0:a:0 -shortest \"{$finalLocalPath}\"";
                \Illuminate\Support\Facades\Process::run($cmd);
            }

            // Add to concat list (FFmpeg requires specific format)
            $safePath = str_replace('\\', '/', $finalLocalPath);
            $concatContent .= "file '{$safePath}'\n";
        }

        if (empty($downloadedFiles)) {
            return null;
        }

        file_put_contents($concatListPath, $concatContent);

        // 3. Run FFmpeg
        $outputFilename = 'project_' . $project->id . '_merged_' . Str::random(6) . '.mp4';
        $publicPath = 'videos/' . $outputFilename;
        $outputPath = storage_path('app/public/' . $publicPath);

        // Ensure output dir exists
        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        // Command: ffmpeg -f concat -safe 0 -i concat.txt -c copy output.mp4
        $command = "{$ffmpeg} -f concat -safe 0 -i \"{$concatListPath}\" -c copy \"{$outputPath}\"";

        // Execute
        $result = Process::run($command);

        if ($result->successful()) {
            // Clean up temp files
            foreach ($downloadedFiles as $file) {
                @unlink($file);
            }
            @unlink($concatListPath);
            @rmdir($tempDir);

            // Return relative URL for storage
            return '/storage/' . $publicPath;
        } else {
            \Illuminate\Support\Facades\Log::error('FFmpeg Stitching Failed', [
                'command' => $command,
                'output' => $result->output(),
                'error' => $result->errorOutput(),
                'exit_code' => $result->exitCode(),
            ]);
            return null;
        }
    }

    private function getFfmpegPath(): string
    {
        // Check if it is in the PATH
        $result = @exec("where ffmpeg 2>&1");
        if (!empty($result) && strpos($result, 'Could not find') === false) {
            return 'ffmpeg';
        }

        // Standard user AppData folder paths on Windows
        $userProfile = getenv('USERPROFILE') ?: 'C:\\Users\\HP';
        $possiblePaths = [
            $userProfile . '\\AppData\\Local\\Microsoft\\WinGet\\Links\\ffmpeg.exe',
            'C:\\ffmpeg\\bin\\ffmpeg.exe',
            'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return '"' . $path . '"';
            }
        }

        return 'ffmpeg';
    }
}
