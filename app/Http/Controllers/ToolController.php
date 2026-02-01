<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolRun;
use App\Models\ToolPreset;
use App\Models\Tag;
use App\Services\ToolRunnerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    public function history()
    {
        $runs = Auth::user()->toolRuns()->with('tool')->latest()->paginate(20);
        return view('tools.history', compact('runs'));
    }

    public function index(Request $request)
    {
        $query = Tool::query()->where('status', true);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->boolean('favorite') && Auth::check()) {
            $query->whereHas('favoritedBy', function ($q) {
                $q->where('users.id', Auth::id());
            });
        }

        $tools = $query->with(['tags', 'category'])->latest()->paginate(12);
        $categories = Category::where('type', 'tool')->get();
        $tags = Tag::orderBy('name')->get();

        return view('tools.index', compact('tools', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $tool = Tool::with('tags')->where('slug', $slug)->where('status', true)->firstOrFail();

        $presets = collect();
        $isFavorite = false;

        if (Auth::check()) {
            $user = Auth::user();
            $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
            $isTeam = $user->plan === 'team' || $trialActive;

            $presets = ToolPreset::where('tool_id', $tool->id)
                ->where(function ($query) use ($user, $isTeam) {
                    $query->where('user_id', $user->id)
                        ->orWhere('visibility', 'public');

                    if ($isTeam) {
                        $query->orWhere('visibility', 'team');
                    }
                })
                ->latest()
                ->get();

            $isFavorite = $user->favoriteTools()->where('tool_id', $tool->id)->exists();
        }

        return view('tools.show', compact('tool', 'presets', 'isFavorite'));
    }

    public function run(Request $request, $slug, ToolRunnerService $runner)
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();
        $rules = [
            'input' => 'required|string|max:2000',
            'tone' => 'nullable|string|max:50',
            'length' => 'nullable|string|max:50',
            'format' => 'nullable|string|max:50',
        ];

        $schema = $tool->input_schema ?? [];
        foreach ($schema as $field) {
            $name = $field['name'] ?? null;
            if (!$name) {
                continue;
            }

            $isRequired = (bool) ($field['required'] ?? false);
            $type = $field['type'] ?? 'text';

            $typeRule = match ($type) {
                'number' => 'numeric',
                default => 'string',
            };

            $rules[$name] = ($isRequired ? 'required' : 'nullable') . '|' . $typeRule . '|max:2000';
        }

        $request->validate($rules);

        try {
            $run = $runner->run($tool, $request->all(), Auth::id());

            if (str_starts_with((string) $run->output_text, 'Error')) {
                return response()->json([
                    'status' => 'error',
                    'message' => $run->output_text,
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'output' => $run->output_text,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function stream(Request $request, $slug, ToolRunnerService $runner)
    {
        $tool = Tool::where('slug', $slug)->firstOrFail();

        if ($tool->tool_type === 'video') {
            return response()->json([
                'status' => 'error',
                'message' => 'Streaming is not available for video tools.'
            ], 400);
        }

        $rules = [
            'input' => 'required|string|max:2000',
            'tone' => 'nullable|string|max:50',
            'length' => 'nullable|string|max:50',
            'format' => 'nullable|string|max:50',
        ];

        $schema = $tool->input_schema ?? [];
        foreach ($schema as $field) {
            $name = $field['name'] ?? null;
            if (!$name) {
                continue;
            }

            $isRequired = (bool) ($field['required'] ?? false);
            $type = $field['type'] ?? 'text';

            $typeRule = match ($type) {
                'number' => 'numeric',
                default => 'string',
            };

            $rules[$name] = ($isRequired ? 'required' : 'nullable') . '|' . $typeRule . '|max:2000';
        }

        $request->validate($rules);

        try {
            $run = $runner->startStreamRun($tool, $request->all(), Auth::id());

            return response()->stream(function () use ($runner, $tool, $run, $request) {
                $runner->streamToOutput($tool, $request->all(), $run, function ($chunk) {
                    echo $chunk;
                    @ob_flush();
                    flush();
                });
            }, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'X-Run-Id' => $run->id,
                'Cache-Control' => 'no-cache, no-transform',
                'X-Accel-Buffering' => 'no',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function toggleFavorite($slug)
    {
        $tool = Tool::where('slug', $slug)->where('status', true)->firstOrFail();
        $user = Auth::user();

        if ($user->favoriteTools()->where('tool_id', $tool->id)->exists()) {
            $user->favoriteTools()->detach($tool->id);
            return response()->json(['status' => 'removed']);
        }

        $user->favoriteTools()->attach($tool->id);
        return response()->json(['status' => 'added']);
    }

    public function storePreset(Request $request, $slug)
    {
        $tool = Tool::where('slug', $slug)->where('status', true)->firstOrFail();

        $data = $request->validate([
            'name' => 'required|string|max:80',
            'visibility' => 'required|in:private,team,public',
            'input_data' => 'required|array',
        ]);

        $user = Auth::user();
        $trialActive = $user->trial_ends_at && now()->lt($user->trial_ends_at);
        if ($data['visibility'] === 'team' && $user->plan !== 'team' && !$trialActive) {
            return response()->json(['status' => 'error', 'message' => 'Team templates require a Team plan.'], 403);
        }

        $preset = ToolPreset::create([
            'tool_id' => $tool->id,
            'user_id' => $user->id,
            'name' => $data['name'],
            'input_data' => $data['input_data'],
            'visibility' => $data['visibility'],
        ]);

        return response()->json(['status' => 'success', 'preset' => $preset]);
    }

    public function deletePreset(ToolPreset $preset)
    {
        if ($preset->user_id !== Auth::id()) {
            abort(403);
        }

        $preset->delete();

        return response()->json(['status' => 'success']);
    }

    public function showRun(ToolRun $run)
    {
        if ($run->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tools.show-run', compact('run'));
    }

    public function checkStatus(Request $request, $formattedId)
    {
        $rawId = trim(urldecode($formattedId));

        if (str_starts_with($rawId, 'RUN:')) {
            $runId = (int) str_replace('RUN:', '', $rawId);
            $run = ToolRun::find($runId);

            if (!$run) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Run not found.'
                ], 404);
            }

            if ($run->status === 'queued') {
                return response()->json([
                    'status' => 'queued',
                ]);
            }

            if (str_starts_with((string) $run->output_text, 'VIDEO_GENERATION_STARTED')) {
                $predictionId = str_replace('VIDEO_GENERATION_STARTED: ', '', (string) $run->output_text);
                $predictionId = trim($predictionId);

                try {
                    $service = new \App\Services\ReplicateService();
                    $status = $service->checkStatus($predictionId);

                    if (($status['status'] ?? null) === 'succeeded' && !empty($status['output'])) {
                        $run->update([
                            'status' => 'completed',
                            'output_text' => $status['output'],
                        ]);
                    }

                    if (in_array($status['status'] ?? null, ['failed', 'canceled'], true)) {
                        $run->update([
                            'status' => 'failed',
                            'output_text' => 'Error: Video generation failed. ' . ($status['error'] ?? 'Unknown error'),
                        ]);
                    }

                    return response()->json([
                        'status' => 'success',
                        'data' => $status,
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ], 500);
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'status' => $run->status,
                    'output' => $run->output_text,
                ]
            ]);
        }

        // Support both "VIDEO_GENERATION_STARTED: <id>" and just "<id>"
        $predictionId = str_replace('VIDEO_GENERATION_STARTED: ', '', $rawId);
        $predictionId = trim($predictionId); // Safety trim

        try {
            $service = new \App\Services\ReplicateService();
            $status = $service->checkStatus($predictionId);

            return response()->json([
                'status' => 'success',
                'data' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
