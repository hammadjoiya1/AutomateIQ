<?php

namespace App\Http\Controllers;

use App\Models\VideoProject;
use App\Services\VideoGenerationService;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function __construct(
        protected VideoGenerationService $videoService
    ) {
    }

    public function index()
    {
        $projects = auth()->user()->videoProjects()->latest()->get();
        return view('videos.index', compact('projects'));
    }

    public function create()
    {
        $hasToken = !empty(config('services.replicate.api_token')) || !empty(env('REPLICATE_API_TOKEN'));
        return view('videos.create', compact('hasToken'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'nullable|required_if:mode,simple|string|min:10',
            'script' => 'nullable|required_if:mode,script|string|min:20',
            'visual_style' => 'required|string',
            'quality' => 'required|in:standard,hd,premium',
            'mode' => 'required|in:simple,script',
        ]);

        $mode = $validated['mode'];
        $quality = $validated['quality'];

        // COMMON PROJECT CREATION
        $project = auth()->user()->videoProjects()->create([
            'title' => $mode === 'script' ? 'Script to Series' : 'Text to Video',
            'prompt' => $mode === 'simple' ? $validated['prompt'] : 'Multi-scene script',
            'script_content' => $mode === 'script' ? $validated['script'] : null,
            'visual_style' => $validated['visual_style'],
            'model_provider' => 'replicate',
            'status' => 'generating',
            'settings' => ['quality' => $quality, 'mode' => $mode],
        ]);

        if ($mode === 'simple') {
            // SINGLE VIDEO GENERATION
            $this->videoService->generate($project);
        } else {
            // SCRIPT TO SERIES GENERATION
            $lines = array_filter(array_map('trim', explode("\n", $validated['script'])));
            
            // If the user pasted a massive block of text without newlines, split it intelligently
            if (count($lines) === 1) {
                $text = $validated['script'];
                // Check if there are timecodes like 0:00-0:10
                if (preg_match('/\d+:\d+-\d+:\d+/', $text)) {
                    // Split before each timecode
                    $lines = preg_split('/(?=\d+:\d+-\d+:\d+)/', $text, -1, PREG_SPLIT_NO_EMPTY);
                } else {
                    // Split by sentences (period, question mark, or exclamation mark followed by space)
                    $lines = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
                }
                $lines = array_filter(array_map('trim', $lines));
            }

            $sequence = 1;

            foreach ($lines as $line) {
                if (strlen($line) < 5)
                    continue; // Skip very short lines

                $scene = $project->scenes()->create([
                    'sequence_order' => $sequence++,
                    'script_text' => $line,
                    'image_prompt' => $line, // Use script line as prompt for now
                    'status' => 'pending',
                ]);
            }
            
            // Fire the first scene immediately
            $firstScene = $project->scenes()->orderBy('sequence_order')->first();
            if ($firstScene) {
                $this->videoService->generateScene($firstScene);
            }
        }

        // Redirect to show page where we will list the scenes
        return redirect()->route('videos.show', $project)
            ->with('message', 'Video generation started! This may take a few minutes.');
    }

    public function show(VideoProject $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }
        return view('videos.show', compact('project'));
    }

    public function checkStatus(VideoProject $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }

        // Check project-level status (single video)
        if ($project->settings['mode'] === 'simple') {
            $result = app(VideoGenerationService::class)->checkStatus($project);
            return response()->json([
                'status' => $project->fresh()->status,
                'video_url' => $project->fresh()->video_url,
                'result' => $result,
            ]);
        }

        // Multi-scene status check
        $scenes = $project->scenes;
        $hasFailures = false;
        $isStillGenerating = false;

        foreach ($scenes as $scene) {
            if ($scene->status === 'generating') {
                // Check Replicate
                app(VideoGenerationService::class)->checkSceneStatus($scene);
                $scene->refresh();
            }

            if ($scene->status === 'generating' || $scene->status === 'pending') {
                $isStillGenerating = true;
            }

            if ($scene->status === 'failed') {
                $hasFailures = true;
            }
        }

        // Process scenes sequentially to avoid Replicate rate limits (6 req/min)
        $generatingCount = $scenes->where('status', 'generating')->count();
        if ($generatingCount === 0) {
            $nextPending = $scenes->where('status', 'pending')->sortBy('sequence_order')->first();
            if ($nextPending) {
                app(VideoGenerationService::class)->generateScene($nextPending);
                $isStillGenerating = true; // We just started one
            }
        }

        // Only aggregate status if all scenes have finished processing
        if (!$isStillGenerating) {
            if (!$hasFailures && !$project->video_url) {
                // All scenes done successfully, stitch them!
                $finalUrl = app(\App\Services\VideoStitchingService::class)->stitch($project);
                if ($finalUrl) {
                    $project->update([
                        'status' => 'completed',
                        'video_url' => $finalUrl,
                        'completed_at' => now(),
                    ]);
                } else {
                    $project->update(['status' => 'failed']);
                }
            } elseif ($hasFailures) {
                // Some or all scenes failed
                $project->update(['status' => 'failed']);
            }
        }

        return response()->json([
            'status' => $project->fresh()->status,
            'video_url' => $project->fresh()->video_url,
            // 'logs' => ... (aggregate logs?)
        ]);
    }
}
