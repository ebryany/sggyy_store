<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing OSS Storage...\n\n";

$disk = \Illuminate\Support\Facades\Storage::disk('oss');
echo "Disk type: " . get_class($disk) . "\n\n";

// Test exists() method (should work with Flysystem)
try {
    $exists = $disk->exists('test.txt');
    echo "✅ exists() method works\n";
    echo "   File 'test.txt' exists: " . ($exists ? 'Yes' : 'No') . "\n";
} catch (\Exception $e) {
    echo "❌ exists() ERROR: " . $e->getMessage() . "\n";
}

// Test put() method
try {
    $put = $disk->put('test-oss-' . time() . '.txt', 'OSS Test Content');
    echo "✅ put() method works\n";
    echo "   File written: " . ($put ? 'Yes' : 'No') . "\n";
} catch (\Exception $e) {
    echo "❌ put() ERROR: " . $e->getMessage() . "\n";
}

// Test files() - this should work with Laravel FilesystemManager
try {
    $files = $disk->files('');
    echo "✅ files() method works\n";
    echo "   Found " . count($files) . " files\n";
} catch (\Exception $e) {
    echo "❌ files() ERROR: " . $e->getMessage() . "\n";
    echo "   This means Storage::disk() returns Flysystem directly, not FilesystemManager\n";
}

