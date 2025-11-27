<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔧 OSS Connection Fix Test\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Get config
$accessKeyId = env('OSS_ACCESS_KEY_ID');
$accessKeySecret = env('OSS_ACCESS_KEY_SECRET');
$endpoint = env('OSS_ENDPOINT');
$bucket = env('OSS_BUCKET');

echo "📋 Configuration Check:\n";
echo "   Access Key ID: " . ($accessKeyId ? substr($accessKeyId, 0, 10) . '...' : '❌ NOT SET') . "\n";
echo "   Access Key Secret: " . ($accessKeySecret ? '***SET***' : '❌ NOT SET') . "\n";
echo "   Endpoint: " . ($endpoint ?: '❌ NOT SET') . "\n";
echo "   Bucket: " . ($bucket ?: '❌ NOT SET') . "\n\n";

if (empty($accessKeyId) || empty($accessKeySecret) || empty($endpoint) || empty($bucket)) {
    echo "❌ Configuration incomplete!\n";
    exit(1);
}

// Test 1: Basic OSS Client Creation
echo "🔧 Test 1: OSS Client Creation\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $client = new \OSS\OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
    echo "✅ OSS Client created successfully\n\n";
} catch (\Exception $e) {
    echo "❌ Failed to create OSS Client\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Class: " . get_class($e) . "\n";
    exit(1);
}

// Test 2: Check if bucket exists
echo "🔧 Test 2: Bucket Existence Check\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $exists = $client->doesBucketExist($bucket);
    echo "   Bucket '{$bucket}' exists: " . ($exists ? '✅ Yes' : '❌ No') . "\n";
    if (!$exists) {
        echo "   ⚠️  Bucket does not exist! Please create it in Alibaba Cloud Console.\n";
    }
} catch (\Exception $e) {
    echo "❌ Failed to check bucket existence\n";
    echo "   Error: " . $e->getMessage() . "\n";
    if ($e instanceof \OSS\Core\OssException) {
        echo "   OSS Error Code: " . $e->getErrorCode() . "\n";
        echo "   HTTP Status: " . $e->getHTTPStatus() . "\n";
    }
}

echo "\n";

// Test 3: Test write with small file
echo "🔧 Test 3: Write Test (Small File)\n";
echo "───────────────────────────────────────────────────────────────\n";
$testPath = 'test/connection-test-' . time() . '.txt';
$testContent = 'OSS Connection Test - ' . date('Y-m-d H:i:s');

try {
    echo "   Writing to: {$testPath}\n";
    $result = $client->putObject($bucket, $testPath, $testContent);
    echo "   ✅ Write SUCCESS!\n";
    echo "   Result: " . var_export($result, true) . "\n";
    
    // Verify
    $exists = $client->doesObjectExist($bucket, $testPath);
    echo "   File exists check: " . ($exists ? '✅ Yes' : '❌ No') . "\n";
    
    if ($exists) {
        $readContent = $client->getObject($bucket, $testPath);
        echo "   Read content: " . ($readContent === $testContent ? '✅ Matches' : '❌ Mismatch') . "\n";
        
        // Cleanup
        $client->deleteObject($bucket, $testPath);
        echo "   Cleanup: ✅ Test file deleted\n";
    }
    
} catch (\OSS\Core\OssException $e) {
    echo "❌ OSS Write FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   OSS Error Code: " . ($e->getErrorCode() ?: 'N/A') . "\n";
    echo "   HTTP Status: " . ($e->getHTTPStatus() ?: 'N/A') . "\n";
    echo "   Request ID: " . ($e->getRequestId() ?: 'N/A') . "\n";
    
    // Common error solutions
    echo "\n💡 Possible Solutions:\n";
    if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'reset')) {
        echo "   1. Network/Firewall issue - Check if server can reach OSS endpoint\n";
        echo "   2. Proxy settings - Configure proxy if behind firewall\n";
        echo "   3. DNS resolution - Check if endpoint resolves correctly\n";
    }
    if (str_contains($e->getMessage(), 'AccessDenied') || str_contains($e->getMessage(), '403')) {
        echo "   1. Check Access Key permissions in Alibaba Cloud Console\n";
        echo "   2. Verify bucket ACL settings\n";
        echo "   3. Check if Access Key has OSS write permissions\n";
    }
    if (str_contains($e->getMessage(), 'InvalidAccessKeyId') || str_contains($e->getMessage(), '401')) {
        echo "   1. Verify Access Key ID and Secret are correct\n";
        echo "   2. Check if Access Key is active in Alibaba Cloud Console\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Write FAILED (General Exception)\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Class: " . get_class($e) . "\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";

