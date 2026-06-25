<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Workflow;
use App\Models\WorkflowRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class WorkflowController extends Controller
{
    public function index()
    {
        $workflows = Auth::user()->workflows()->latest()->paginate(10);
        $hasWorkflowRuns = Schema::hasTable('workflow_runs');
        return view('workflows.index', compact('workflows', 'hasWorkflowRuns'));
    }

    public function create()
    {
        $tools = Tool::where('status', true)->get();
        return view('workflows.create', compact('tools'));
    }

    public function edit(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $tools = Tool::where('status', true)->get();
        $steps = $workflow->steps()->orderBy('order')->get()->map(function ($step) {
            return [
                'tool_id' => $step->tool_id,
                'input' => $step->config['input'] ?? '',
            ];
        })->values();

        return view('workflows.create', compact('tools', 'workflow', 'steps'));
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

    public function update(Request $request, Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'steps' => 'required|array|min:1',
                'steps.*.tool_id' => 'required|exists:tools,id',
            ]);

            $workflow->update([
                'name' => $request->name,
                'schedule' => $request->schedule ?: null,
            ]);

            $workflow->steps()->delete();

            foreach ($request->steps as $index => $stepData) {
                if (empty($stepData['tool_id'])) {
                    continue;
                }

                $workflow->steps()->create([
                    'tool_id' => $stepData['tool_id'],
                    'order' => $index + 1,
                    'config' => ['input' => $stepData['input'] ?? ''],
                ]);
            }

            return redirect()->route('workflows.index')->with('success', 'Workflow updated!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Workflow Update Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update workflow: ' . $e->getMessage())->withInput();
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

            $run = WorkflowRun::create([
                'workflow_id' => $workflow->id,
                'user_id' => Auth::id(),
                'input_data' => $input,
                'status' => 'queued',
            ]);

            \App\Jobs\ExecuteWorkflow::dispatch($run->id);

            return redirect()->route('workflows.runs.show', $run)
                ->with('success', 'Workflow started! Results will appear here shortly.');

        } catch (\Exception $e) {
            return back()->with('error', 'Workflow failed: ' . $e->getMessage());
        }
    }

    public function showRun(WorkflowRun $run)
    {
        if ($run->user_id !== Auth::id()) {
            abort(403);
        }

        $workflow = $run->workflow()->with('steps.tool')->first();

        return view('workflows.show-run', compact('run', 'workflow'));
    }

    public function runStatus(WorkflowRun $run)
    {
        if ($run->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'status' => $run->status,
            'results' => $run->results,
            'error' => $run->error,
            'updated_at' => $run->updated_at,
        ]);
    }

    public function runs(Workflow $workflow)
    {
        if ($workflow->user_id !== Auth::id()) {
            abort(403);
        }

        $runs = $workflow->runs()->latest()->paginate(10);

        return view('workflows.runs', compact('workflow', 'runs'));
    }
}
