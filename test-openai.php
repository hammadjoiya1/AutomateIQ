<?php

use App\Services\ToolRunnerService;
use App\Models\Tool;

// Get a tool
$tool = Tool::first();

echo "Tool: " . $tool->name . "\n";

// Create service
$service = new ToolRunnerService();

// Try to run
try {
    $result = $service->run($tool, ['input' => 'test'], 1);
    echo "SUCCESS!\n";
    echo "Output: " . $result->output_text . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
