<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$token = config('services.replicate.api_token');
$model = 'cjwbw/damo-text-to-video';

echo "Fetching versions for {$model}...\n";

$response = Http::withToken($token)->get("https://api.replicate.com/v1/models/{$model}/versions");

if ($response->successful()) {
    $versions = $response->json()['results'] ?? [];
    if (count($versions) > 0) {
        $latest = $versions[0];
        echo "Latest Version ID: " . $latest['id'] . "\n";
        echo "Created At: " . $latest['created_at'] . "\n";
    } else {
        echo "No versions found.\n";
    }
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
