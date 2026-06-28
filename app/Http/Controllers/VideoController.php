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
        \Log::info('VideoController@store called', [
            'mode' => $request->input('mode'),
            'quality' => $request->input('quality'),
            'user_credits' => auth()->user()->credits,
            'role' => auth()->user()->role,
        ]);

        $validated = $request->validate([
            'prompt' => 'nullable|required_if:mode,simple|string|min:10',
            'script' => 'nullable|required_if:mode,script|string|min:20',
            'visual_style' => 'required|string',
            'quality' => 'required|in:standard,hd,premium',
            'mode' => 'required|in:simple,script',
        ]);

        $mode = $validated['mode'];
        $quality = $validated['quality'];

        // CREDIT CHECK BEFORE ANYTHING ELSE
        $videoTiers = config('credits.video_tiers', []);
        
        if ($mode === 'simple') {
            $creditsRequired = $videoTiers[$quality]['credits'] ?? 20;
            if (auth()->user()->credits < $creditsRequired) {
                return back()->with('error', "Insufficient credits. You need {$creditsRequired} credits for this video.")->withInput();
            }
            $validScenesCount = 1; // For usage meter
        } else {
            $parser = app(\App\Services\ScriptParserService::class);
            $parsedScenes = $parser->parseScript($validated['script']);
            
            $validScenesCount = collect($parsedScenes)->filter(fn($s) => strlen($s['visual']) >= 5)->count();
            if ($validScenesCount === 0) {
                return back()->with('error', "Could not extract any valid scenes from the script.")->withInput();
            }

            $creditsRequired = ($videoTiers[$quality]['credits'] ?? 20) * $validScenesCount;
            if (auth()->user()->credits < $creditsRequired) {
                return back()->with('error', "Insufficient credits. You need {$creditsRequired} credits for {$validScenesCount} scenes.")->withInput();
            }
        }

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

        // DEDUCT CREDITS & RECORD USAGE
        auth()->user()->debitCredits($creditsRequired);
        app(\App\Services\UsageMeterService::class)->recordToolRun(auth()->user(), true, ($videoTiers[$quality]['cost_cents'] ?? 50) * $validScenesCount, $creditsRequired);

        if ($mode === 'simple') {
            // SINGLE VIDEO GENERATION
            $this->videoService->generate($project);
        } else {
            // SCRIPT TO SERIES GENERATION — Intelligent Parsing
            $sequence = 1;

            foreach ($parsedScenes as $parsed) {
                // Only the VISUAL description goes to the video AI
                $visualPrompt = $parsed['visual'];
                if (strlen($visualPrompt) < 5) {
                    continue; // Skip scenes with no meaningful visual
                }

                $scene = $project->scenes()->create([
                    'sequence_order' => $sequence++,
                    'script_text'    => $parsed['raw'],       // Full original line for reference
                    'image_prompt'   => $visualPrompt,         // Clean visual-only prompt for video AI
                    'status'         => 'pending',
                    'settings'       => [
                        'timecode'   => $parsed['timecode'],
                        'dialogue'   => $parsed['dialogue'],
                        'sound_cues' => $parsed['sound_cues'],
                        'tone'       => $parsed['tone'],
                        'voice'      => $parsed['voice'],
                        'speed'      => $parsed['speed'],
                    ],
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

        if ($project->status === 'generating' || $project->status === 'scripting') {
            return view('videos.generating', compact('project'));
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
            'completed_scenes' => $scenes->where('status', 'completed')->count(),
            'total_scenes' => $scenes->count(),
        ]);
    }
}
