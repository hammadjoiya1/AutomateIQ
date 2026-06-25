<?php

namespace App\Jobs;

use App\Models\Workflow;
use App\Models\WorkflowRun;
use App\Services\WorkflowRunnerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExecuteWorkflow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $runId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $runId)
    {
        $this->runId = $runId;
    }

    /**
     * Execute the job.
     */
    public function handle(WorkflowRunnerService $runner): void
    {
        $run = WorkflowRun::find($this->runId);

        if (!$run) {
            Log::warning('ExecuteWorkflow: WorkflowRun not found', ['run_id' => $this->runId]);
            return;
        }

        $workflow = Workflow::find($run->workflow_id);
        if (!$workflow) {
            Log::warning('ExecuteWorkflow: Workflow not found', ['workflow_id' => $run->workflow_id]);
            return;
        }

        Log::info("Starting workflow: " . $workflow->name);
        $input = $run->input_data ?: ['input' => 'Default Topic'];
        $runner->run($workflow, $input, $run);
        Log::info("Workflow completed.");
    }
}
