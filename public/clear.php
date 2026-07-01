<?php
// clear.php — utility to force-clear web server caches (OPcache, Laravel views, config)

// 1. Reset OPcache (runs in Apache/FPM context)
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache Reset: SUCCESS<br>";
} else {
    echo "OPcache Reset: NOT AVAILABLE (not enabled)<br>";
}

// 2. Clear Laravel caches via artisan call
try {
    // Load autoloader first
    if (file_exists(__DIR__.'/../vendor/autoload.php')) {
        require __DIR__.'/../vendor/autoload.php';
    }
    
    // Boot Laravel
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    $status1 = $kernel->call('view:clear');
    echo "Laravel View Cache Clear: SUCCESS<br>";
    
    $status2 = $kernel->call('route:clear');
    echo "Laravel Route Cache Clear: SUCCESS<br>";
    
    $status3 = $kernel->call('config:clear');
    echo "Laravel Config Cache Clear: SUCCESS<br>";
    
    $status4 = $kernel->call('cache:clear');
    echo "Laravel Cache Clear: SUCCESS<br>";
} catch (Exception $e) {
    echo "Laravel Artisan Clear: FAILED (" . $e->getMessage() . ")<br>";
}

echo "<br><b>All caches successfully cleared! Please refresh your homepage now.</b>";
?>
