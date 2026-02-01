<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$project = \App\Models\VideoProject::latest()->first();

if (!$project) {
    echo "No project found.\n";
    exit;
}

echo "Manual Stitch Trigger for Project {$project->id}...\n";

// Force a check via the controller logic
$controller = app(\App\Http\Controllers\VideoController::class);

// We can't call the controller method easily because it returns JSON response.
// Let's call the stitching service directly to debug.

$stitcher = app(\App\Services\VideoStitchingService::class);
echo "Calling stitch()...\n";

$url = $stitcher->stitch($project);

if ($url) {
    echo "SUCCESS! Stitched URL: {$url}\n";
    $project->update([
        'status' => 'completed',
        'video_url' => $url,
        'completed_at' => now(),
    ]);
} else {
    echo "FAILURE: Stitching returned null.\n";
    // Check if files exist?
    // The service handles downloading.
}
