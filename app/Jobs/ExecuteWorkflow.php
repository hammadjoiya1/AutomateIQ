<?php

namespace App\Jobs;

use App\Models\Workflow;
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

    public $workflow;
    public $initialInput;

    /**
     * Create a new job instance.
     */
    public function __construct(Workflow $workflow, array $initialInput = [])
    {
        $this->workflow = $workflow;
        $this->initialInput = $initialInput;
    }

    /**
     * Execute the job.
     */
    public function handle(WorkflowRunnerService $runner): void
    {
        Log::info("Starting workflow: " . $this->workflow->name);
        $input = $this->initialInput ?: ['input' => 'Default Topic'];
        $runner->run($this->workflow, $input);
        Log::info("Workflow completed.");
    }
}
