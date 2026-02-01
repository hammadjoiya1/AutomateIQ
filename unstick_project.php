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

echo "Checking Project ID: {$project->id}\n";
$service = app(\App\Services\VideoGenerationService::class);

foreach ($project->scenes as $scene) {
    if ($scene->status === 'pending') {
        echo "Retrying Scene {$scene->sequence_order}...\n";
        $id = $service->generateScene($scene);
        if ($id) {
            echo "  -> Started! Prediction ID: {$id}\n";
        } else {
            echo "  -> Failed to start.\n";
        }
    } else {
        echo "Scene {$scene->sequence_order} is {$scene->status}.\n";
    }
}
