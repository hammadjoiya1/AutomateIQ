<?php

namespace App\Http\Controllers;

use App\Models\Script;
use App\Services\OpenAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ScriptController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    /**
     * Display a listing of user's scripts
     */
    public function index()
    {
        $scripts = Auth::user()->scripts()
            ->latest()
            ->paginate(12);

        return view('scripts.index', compact('scripts'));
    }

    /**
     * Show the form for creating a new script
     */
    public function create()
    {
        return view('tools.script-writer');
    }

    /**
     * Generate a new script using AI
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string|max:500',
            'tone' => 'required|in:casual,professional,energetic,humorous',
            'length' => 'required|in:short,medium,long',
            'target_audience' => 'nullable|string|max:200',
            'key_points' => 'nullable|string|max:1000',
        ]);

        try {
            // Generate script using OpenAI
            $result = $this->openAIService->generateScript($validated);

            if (!$result['success']) {
                return back()->with('error', $result['error']);
            }

            // Create title from topic
            $title = Str::limit($validated['topic'], 60, '...');

            // Save to database
            $script = Script::create([
                'user_id' => Auth::id(),
                'title' => $title,
                'topic' => $validated['topic'],
                'tone' => $validated['tone'],
                'length' => $validated['length'],
                'target_audience' => $validated['target_audience'] ?? null,
                'key_points' => $validated['key_points'] ?? null,
                'script_content' => $result['script'],
                'tokens_used' => $result['tokens_used'] ?? 0,
                'metadata' => [
                    'generated_at' => now()->toIso8601String(),
                ],
            ]);

            // Calculate word count
            $script->setWordCount();
            $script->save();

            return redirect()
                ->route('scripts.show', $script)
                ->with('success', 'Script generated successfully!');

        } catch (\Exception $e) {
            \Log::error('Script generation failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to generate script. Please try again.');
        }
    }

    /**
     * Display the specified script
     */
    public function show(Script $script)
    {
        // Ensure user owns this script
        if ($script->user_id !== Auth::id()) {
            abort(403);
        }

        return view('scripts.show', compact('script'));
    }

    /**
     * Remove the specified script
     */
    public function destroy(Script $script)
    {
        // Ensure user owns this script
        if ($script->user_id !== Auth::id()) {
            abort(403);
        }

        $script->delete();

        return redirect()
            ->route('scripts.index')
            ->with('success', 'Script deleted successfully!');
    }
}
