<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Auth::user()->workflows()->latest()->paginate(10);
        return view('workflows.index', compact('workflows'));
    }

    public function create()
    {
        $tools = Tool::where('status', true)->get();
        return view('workflows.create', compact('tools'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Workflow Create Request:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'steps' => 'required|array|min:1',
                'steps.*.tool_id' => 'required|exists:tools,id',
            ]);

            $workflow = Auth::user()->workflows()->create([
                'name' => $request->name,
                'schedule' => $request->schedule ?: null, // Handle empty string
                'active' => true,
            ]);

            foreach ($request->steps as $index => $stepData) {
                // Ensure tool_id is present and valid (already validated but good for safety)
                if (empty($stepData['tool_id']))
                    continue;

                $workflow->steps()->create([
                    'tool_id' => $stepData['tool_id'],
                    'order' => $index + 1,
                    // Use 'config' column, ensure it's array
                    'config' => ['input' => $stepData['input'] ?? ''],
                ]);
            }

            return redirect()->route('workflows.index')->with('success', 'Workflow created!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Workflow Creation Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create workflow: ' . $e->getMessage())->withInput();
        }
    }

    public function run(Workflow $workflow, Request $request, \App\Services\WorkflowRunnerService $runner)
    {
        // 1. Validate
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // 2. Dispatch async workflow execution to avoid request timeouts
            $input = ['input' => $request->input('topic', 'Default Topic')];
            \App\Jobs\ExecuteWorkflow::dispatch($workflow, $input);

            return redirect()->route('workflows.index')->with('success', 'Workflow started! Results will appear in your history shortly.');

        } catch (\Exception $e) {
            return back()->with('error', 'Workflow failed: ' . $e->getMessage());
        }
    }
}
