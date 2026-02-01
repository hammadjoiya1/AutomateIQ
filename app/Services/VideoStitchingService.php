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

        // 2. Download files locally
        foreach ($scenes as $scene) {
            $url = $scene->video_url;
            if (!$url)
                continue;

            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?? 'mp4';
            $filename = "scene_{$scene->sequence_order}.{$extension}";
            $localPath = $tempDir . '/' . $filename;

            // Simple download
            file_put_contents($localPath, file_get_contents($url));
            $downloadedFiles[] = $localPath;

            // Add to concat list (FFmpeg requires specific format)
            // escape backslashes for Windows if needed, but forward slashes usually work with ffmpeg
            $safePath = str_replace('\\', '/', $localPath);
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
        $command = "ffmpeg -f concat -safe 0 -i \"{$concatListPath}\" -c copy \"{$outputPath}\"";

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
            // Log error?
            // For now just return null
            return null;
        }
    }
}
