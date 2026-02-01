<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use HalilCosdu\Replicate\Facades\Replicate;

$versionId = '1e205ea73084bd17a0a3b43396e49ba0d6bc2e754e9283b2df94fad0d58b952a'; // Damo matching service
$input = [
    'prompt' => 'test video',
];

try {
    echo "Attempting to create prediction...\n";
    $response = Replicate::createPrediction([
        'version' => $versionId,
        'input' => $input,
    ]);

    echo "Success (Response Received)!\n";
    echo "Body: " . $response->body() . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (method_exists($e, 'getResponse') && $e->getResponse()) {
        echo "Body: " . $e->getResponse()->getBody()->getContents() . "\n";
    }
}
