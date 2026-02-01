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

echo "Project ID: " . $project->id . "\n";
echo "Status: " . $project->status . "\n";
echo "Scenes: " . $project->scenes()->count() . "\n";

$service = app(\App\Services\VideoGenerationService::class);

foreach ($project->scenes as $scene) {
    echo "  Scene {$scene->sequence_order}: {$scene->status} | Pred ID: " . ($scene->replicate_prediction_id ?? 'NULL') . "\n";

    if ($scene->replicate_prediction_id && $scene->status !== 'completed') {
        echo "    Checking remote status...\n";
        try {
            $res = $service->checkSceneStatus($scene);
            echo "    Remote: " . ($res['status'] ?? 'unknown') . "\n";
        } catch (\Exception $e) {
            echo "    Error: " . $e->getMessage() . "\n";
        }
    }
}
