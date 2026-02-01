<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use HalilCosdu\Replicate\Facades\Replicate;

// Damo Settings (Reliable Fallback)
$versionId = '1e205ea73084bd17a0a3b43396e49ba0d6bc2e754e9283b2df94fad0d58b952a';
$input = [
    'prompt' => 'A beautiful sunset over the ocean',
    // No extra params to avoid 422
];

echo "Attempting generation with Damo settings...\n";

try {
    $response = Replicate::createPrediction([
        'version' => $versionId,
        'input' => $input,
    ]);

    // Check for ID or error
    if (isset($response['id'])) {
        echo "SUCCESS! ID: " . $response['id'] . "\n";
    } else {
        echo "FAILURE (No ID returned).\n";
        echo "Body: " . $response->body() . "\n";
    }

} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
