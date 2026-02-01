<?php

namespace App\Services;

use App\Models\Workflow;
use App\Models\ToolRun;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\UsageMeterService;

class WorkflowRunnerService
{
    protected $toolRunner;

    public function __construct(ToolRunnerService $toolRunner)
    {
        $this->toolRunner = $toolRunner;
    }

    public function run(Workflow $workflow, array $initialInput)
    {
        $currentInput = $initialInput['input'] ?? '';
        $results = [];
        $userId = $workflow->user_id;
        $user = User::find($userId);

        if ($user) {
            app(UsageMeterService::class)->recordWorkflowRun($user);
        }

        Log::info("Starting workflow: {$workflow->name} for user {$userId}");

        // Get steps ordered by sequence
        $steps = $workflow->steps()->orderBy('order')->get();

        foreach ($steps as $step) {
            $tool = $step->tool;

            Log::info("Running step {$step->order}: {$tool->name}");

            try {
                // If it's the first step, use initial input.
                // For subsequent steps, we can either use the initial input OR the output of the previous step.
                // For "Empire" mode, we typically want to chain: Topic -> Hook -> Script.

                // Simple chaining logic:
                // If step > 1, prepend the previous output to the context or use it as input?
                // For V1, let's just pass the 'currentInput' which updates after each step.

                $runInput = ['input' => $currentInput];

                $toolRun = $this->toolRunner->run($tool, $runInput, $userId);

                $results[] = [
                    'step' => $step->order,
                    'tool' => $tool->name,
                    'output' => $toolRun->output_text,
                    'status' => 'success'
                ];

                // Update currentInput for the next step to be the output of this step
                // BUT, strictly chaining (Output -> Input) can be messy if the output is JSON or long.
                // A better approach for "Content Repurposing":
                // Step 1 maps Topic -> Ideas.
                // Step 2 maps Idea -> Hook.
                // So yes, Output -> Input is the standard automation flow.

                $currentInput = $toolRun->output_text;

            } catch (\Exception $e) {
                Log::error("Workflow failed at step {$step->order}: " . $e->getMessage());
                $results[] = [
                    'step' => $step->order,
                    'tool' => $tool->name,
                    'error' => $e->getMessage(),
                    'status' => 'failed'
                ];
                break; // Stop workflow on error
            }
        }

        return $results;
    }
}
