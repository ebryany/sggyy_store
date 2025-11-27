<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Debug OSS Write Operation\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$disk = \Illuminate\Support\Facades\Storage::disk('oss');
$testPath = 'test/debug-' . time() . '.txt';
$testContent = 'Test content for OSS write';

echo "📝 Test Details:\n";
echo "   Path: {$testPath}\n";
echo "   Content: {$testContent}\n";
echo "   Bucket: " . config('filesystems.disks.oss.bucket') . "\n";
echo "   Endpoint: " . config('filesystems.disks.oss.endpoint') . "\n\n";

// Test 1: Direct OSS Client
echo "🔧 Test 1: Direct OSS Client\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $adapter = new \App\Filesystem\OssAdapter([
        'key' => env('OSS_ACCESS_KEY_ID'),
        'secret' => env('OSS_ACCESS_KEY_SECRET'),
        'endpoint' => env('OSS_ENDPOINT'),
        'bucket' => env('OSS_BUCKET'),
        'url' => env('OSS_URL'),
    ]);
    
    // Access private client
    $reflection = new ReflectionClass($adapter);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $client = $clientProperty->getValue($adapter);
    
    echo "   OSS Client created: ✅\n";
    echo "   Testing direct putObject...\n";
    
    $result = $client->putObject(env('OSS_BUCKET'), $testPath, $testContent);
    echo "   ✅ Direct putObject SUCCESS!\n";
    echo "   Result: " . var_export($result, true) . "\n";
    
} catch (\Exception $e) {
    echo "   ❌ Direct putObject FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Class: " . get_class($e) . "\n";
    if ($e instanceof \OSS\Core\OssException) {
        echo "   OSS Error Code: " . $e->getErrorCode() . "\n";
        echo "   OSS Error Message: " . $e->getErrorMessage() . "\n";
        echo "   Request ID: " . $e->getRequestId() . "\n";
    }
}

echo "\n";

// Test 2: Via Laravel Storage
echo "🔧 Test 2: Via Laravel Storage\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    echo "   Testing Storage::disk('oss')->put()...\n";
    $result = $disk->put($testPath, $testContent);
    echo "   ✅ Storage put() SUCCESS!\n";
    echo "   Return value: " . var_export($result, true) . "\n";
    
    // Check if file exists
    $exists = $disk->exists($testPath);
    echo "   File exists check: " . ($exists ? '✅ Yes' : '❌ No') . "\n";
    
    if ($exists) {
        $readContent = $disk->get($testPath);
        echo "   Read content: " . ($readContent === $testContent ? '✅ Matches' : '❌ Mismatch') . "\n";
    }
    
} catch (\Exception $e) {
    echo "   ❌ Storage put() FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Class: " . get_class($e) . "\n";
    if ($e->getPrevious()) {
        echo "   Previous: " . $e->getPrevious()->getMessage() . "\n";
    }
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";

