<?php

namespace Tests\Feature;

use App\Jobs\ExecuteWorkflow;
use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_workflow_job_executes_steps()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'General', 'slug' => 'general', 'type' => 'tool']);

        $tool = Tool::create([
            'name' => 'Test Tool',
            'slug' => 'test-tool',
            'description' => 'Test tool',
            'category_id' => $category->id,
            'status' => true,
        ]);

        $workflow = Workflow::create([
            'user_id' => $user->id,
            'name' => 'Daily Test Workflow',
            'active' => true,
        ]);

        $workflow->steps()->create([
            'tool_id' => $tool->id,
            'order' => 1,
            'config' => ['input' => 'Step 1 Input']
        ]);

        $workflow->steps()->create([
            'tool_id' => $tool->id,
            'order' => 2,
            'config' => ['input' => 'Step 2 Input']
        ]);

        (new ExecuteWorkflow($workflow))->handle(new \App\Services\ToolRunnerService());

        $this->assertDatabaseCount('tool_runs', 2);
    }
}
