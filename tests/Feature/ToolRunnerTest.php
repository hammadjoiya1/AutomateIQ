<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use App\Services\ToolRunnerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ToolRunnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_tool_runner_executes_and_stores_run()
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'General', 'slug' => 'general', 'type' => 'tool']);

        $tool = Tool::create([
            'name' => 'Test Generator',
            'slug' => 'test-generator',
            'description' => 'A test tool',
            'category_id' => $category->id,
            'tool_type' => 'generator',
            'status' => true,
        ]);

        $service = new ToolRunnerService();
        $inputs = ['input' => 'Test Topic'];

        $run = $service->run($tool, $inputs, $user->id);

        $this->assertDatabaseHas('tool_runs', [
            'tool_id' => $tool->id,
            'user_id' => $user->id,
        ]);

        $this->assertStringContainsString('Test Topic', $run->output_text);
    }
}
