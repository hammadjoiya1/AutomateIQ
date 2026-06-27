<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\VideoGenerationService;
use App\Models\VideoProject;
use App\Models\User;

echo "=========================================\n";
echo "VIDEO MODEL TIER TESTING SCRIPT\n";
echo "=========================================\n";

$tiers = config('credits.video_tiers', []);
if (empty($tiers)) {
    echo "Error: config/credits.php does not contain any video_tiers config. Please run 'php artisan config:clear'.\n";
    exit(1);
}

echo "Available Tiers:\n";
foreach ($tiers as $key => $tier) {
    echo "  - {$key}: model='{$tier['model']}', cost={$tier['cost_cents']}c, credits={$tier['credits']}\n";
}
echo "=========================================\n";

// Get inputs
echo "Enter tier to test (standard | hd | premium) [default: hd]: ";
$tierInput = trim(fgets(STDIN)) ?: 'hd';
$tierInput = strtolower(trim($tierInput));

if (!isset($tiers[$tierInput])) {
    echo "Invalid tier selection. Defaulting to 'hd'.\n";
    $tierInput = 'hd';
}

echo "Enter prompt [default: 'A majestic eagle flying over mountains']: ";
$promptInput = trim(fgets(STDIN)) ?: 'A majestic eagle flying over mountains';
$promptInput = trim($promptInput);

$selectedTier = $tiers[$tierInput];
echo "\nTesting Tier: " . strtoupper($tierInput) . "\n";
echo "Model: {$selectedTier['model']}\n";
echo "Prompt: \"{$promptInput}\"\n";
echo "=========================================\n";

try {
    // Check API token
    $token = config('services.replicate.api_token');
    if (empty($token)) {
        echo "WARNING: Replicate API token is not configured in services.replicate.api_token. Checking env...\n";
        $token = env('REPLICATE_API_TOKEN');
    }
    if (empty($token)) {
        throw new \Exception("Replicate API token is empty. Please set REPLICATE_API_TOKEN in your .env file.");
    }
    echo "Replicate API Token: Present (" . substr($token, 0, 8) . "...)\n";

    // Find or create test user
    $user = User::where('role', 'admin')->first() ?: User::first();
    if (!$user) {
        throw new \Exception("No user found in the database. Please register/create a user first.");
    }

    echo "Using User: {$user->email} (ID: {$user->id})\n";

    // Create a temporary video project in DB
    $project = VideoProject::create([
        'user_id' => $user->id,
        'title' => 'Test Video Tier: ' . $tierInput,
        'prompt' => $promptInput,
        'visual_style' => 'realistic',
        'model_provider' => 'replicate',
        'status' => 'pending',
        'settings' => [
            'quality' => $tierInput,
            'mode' => 'simple',
        ],
    ]);

    echo "Created database test project with ID: {$project->id}\n";
    echo "Attempting to start generation...\n";

    $service = app(VideoGenerationService::class);
    $predictionId = $service->generate($project);

    if ($predictionId) {
        echo "SUCCESS! Video generation started successfully.\n";
        echo "Replicate Prediction ID: {$predictionId}\n";
        echo "Database project status: " . $project->fresh()->status . "\n";
        echo "Check status in browser or via route /videos/{$project->id}\n";
    } else {
        echo "FAILED: VideoGenerationService::generate returned null. Check Laravel logs for details.\n";
    }

} catch (\Throwable $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
