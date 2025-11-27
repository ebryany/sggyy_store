<?php

/**
 * OSS Credentials Validation Test
 * 
 * Test untuk memvalidasi OSS credentials dan permission
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use OSS\OssClient;
use OSS\Core\OssException;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  🔐 OSS CREDENTIALS VALIDATION TEST\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

// Get credentials from .env
$accessKeyId = env('OSS_ACCESS_KEY_ID');
$accessKeySecret = env('OSS_ACCESS_KEY_SECRET');
$endpoint = env('OSS_ENDPOINT');
$bucket = env('OSS_BUCKET');

echo "📋 Configuration:\n";
echo "   Access Key ID: " . ($accessKeyId ? substr($accessKeyId, 0, 15) . '...' : '❌ NOT SET') . "\n";
echo "   Access Key Secret: " . ($accessKeySecret ? (strlen($accessKeySecret) > 0 ? '***SET*** (' . strlen($accessKeySecret) . ' chars)' : '❌ EMPTY') : '❌ NOT SET') . "\n";
echo "   Endpoint: " . ($endpoint ?: '❌ NOT SET') . "\n";
echo "   Bucket: " . ($bucket ?: '❌ NOT SET') . "\n\n";

// Check if credentials are the same (this is suspicious)
if ($accessKeyId === $accessKeySecret) {
    echo "⚠️  WARNING: Access Key ID and Secret are the same!\n";
    echo "   This is unusual. OSS credentials should be different.\n";
    echo "   Please verify your credentials in Alibaba Cloud Console.\n\n";
}

if (empty($accessKeyId) || empty($accessKeySecret) || empty($endpoint) || empty($bucket)) {
    echo "❌ Configuration incomplete!\n";
    echo "   Please set all required OSS environment variables in .env\n";
    exit(1);
}

// Test 1: Create OSS Client
echo "🔧 Test 1: OSS Client Creation\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $client = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
    $client->setUseSSL(true);
    $client->setTimeout(30); // Request timeout: 30 seconds
    $client->setConnectTimeout(10); // Connection timeout: 10 seconds
    echo "✅ OSS Client created successfully\n\n";
} catch (OssException $e) {
    echo "❌ Failed to create OSS Client\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Error Code: " . ($e->getErrorCode() ?: 'N/A') . "\n";
    exit(1);
} catch (\Exception $e) {
    echo "❌ Failed to create OSS Client\n";
    echo "   Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check if bucket exists (read permission)
echo "🔧 Test 2: Bucket Existence Check (Read Permission)\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $exists = $client->doesBucketExist($bucket);
    if ($exists) {
        echo "✅ Bucket '{$bucket}' exists\n";
        echo "   ✅ Read permission: OK\n\n";
    } else {
        echo "❌ Bucket '{$bucket}' does not exist\n";
        echo "   Please check bucket name in .env\n\n";
        exit(1);
    }
} catch (OssException $e) {
    echo "❌ Failed to check bucket existence\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Error Code: " . ($e->getErrorCode() ?: 'N/A') . "\n";
    
    $errorMsg = $e->getMessage();
    $errorCode = $e->getErrorCode();
    
    if ($errorCode === 'SignatureDoesNotMatch') {
        echo "\n   💡 SOLUTION:\n";
        echo "      1. Check if Access Key ID and Secret are correct\n";
        echo "      2. Verify credentials in Alibaba Cloud Console\n";
        echo "      3. Make sure credentials are not expired\n";
        echo "      4. Regenerate credentials if needed\n";
    } elseif (str_contains($errorMsg, 'Connection') || str_contains($errorMsg, 'reset') || str_contains($errorMsg, 'timeout')) {
        echo "\n   💡 NETWORK CONNECTION ISSUE:\n";
        echo "      This is a network/firewall problem, not a credentials issue.\n";
        echo "      Solutions:\n";
        echo "      1. Check internet connection\n";
        echo "      2. Temporarily disable Windows Firewall/Antivirus\n";
        echo "      3. Check if OSS endpoint is accessible:\n";
        echo "         ping {$endpoint}\n";
        echo "      4. Try using HTTP instead of HTTPS (not recommended for production)\n";
        echo "      5. Check proxy settings if behind corporate firewall\n";
        echo "      6. Test from server/VPS instead of local machine\n";
        echo "\n   ⚠️  NOTE: This might work on your server even if it fails locally.\n";
        echo "      Network restrictions on local machine don't affect server deployment.\n";
    }
    exit(1);
}

// Test 3: List objects (read permission)
echo "🔧 Test 3: List Objects (Read Permission)\n";
echo "───────────────────────────────────────────────────────────────\n";
try {
    $result = $client->listObjects($bucket, ['max-keys' => 1]);
    echo "✅ List objects: OK\n";
    echo "   Found " . count($result->getObjectList()) . " object(s)\n\n";
} catch (OssException $e) {
    echo "❌ Failed to list objects\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Error Code: " . ($e->getErrorCode() ?: 'N/A') . "\n";
    
    if ($e->getErrorCode() === 'AccessDenied') {
        echo "\n   💡 SOLUTION:\n";
        echo "      Your credentials don't have READ permission.\n";
        echo "      Please check IAM policy in Alibaba Cloud Console.\n";
    }
    exit(1);
}

// Test 4: Write test file (write permission)
echo "🔧 Test 4: Write Test File (Write Permission)\n";
echo "───────────────────────────────────────────────────────────────\n";
$testFileName = 'test/credentials-test-' . time() . '.txt';
$testContent = 'OSS Credentials Test - ' . date('Y-m-d H:i:s');

try {
    $result = $client->putObject($bucket, $testFileName, $testContent);
    echo "✅ Write file: OK\n";
    echo "   File: {$testFileName}\n";
    
    // Verify file exists
    sleep(1); // Wait for eventual consistency
    $exists = $client->doesObjectExist($bucket, $testFileName);
    if ($exists) {
        echo "   ✅ File verified: exists\n";
        
        // Clean up: delete test file
        try {
            $client->deleteObject($bucket, $testFileName);
            echo "   ✅ Test file cleaned up\n\n";
        } catch (\Exception $e) {
            echo "   ⚠️  Warning: Could not delete test file\n\n";
        }
    } else {
        echo "   ⚠️  Warning: File written but not immediately visible (eventual consistency)\n\n";
    }
} catch (OssException $e) {
    echo "❌ Failed to write file\n";
    echo "   Error: " . $e->getMessage() . "\n";
    echo "   Error Code: " . ($e->getErrorCode() ?: 'N/A') . "\n";
    
    if ($e->getErrorCode() === 'SignatureDoesNotMatch') {
        echo "\n   💡 SOLUTION:\n";
        echo "      Your OSS credentials are INVALID or EXPIRED.\n";
        echo "      Steps to fix:\n";
        echo "      1. Go to Alibaba Cloud Console → RAM → Users\n";
        echo "      2. Find your user or create AccessKey\n";
        echo "      3. Copy AccessKey ID and AccessKey Secret\n";
        echo "      4. Update .env file:\n";
        echo "         OSS_ACCESS_KEY_ID=your_new_access_key_id\n";
        echo "         OSS_ACCESS_KEY_SECRET=your_new_access_key_secret\n";
        echo "      5. Run: php artisan config:clear\n";
    } elseif ($e->getErrorCode() === 'AccessDenied') {
        echo "\n   💡 SOLUTION:\n";
        echo "      Your credentials don't have WRITE permission.\n";
        echo "      Steps to fix:\n";
        echo "      1. Go to Alibaba Cloud Console → RAM → Policies\n";
        echo "      2. Attach 'AliyunOSSFullAccess' policy to your user\n";
        echo "      3. Or create custom policy with PutObject permission\n";
    }
    exit(1);
}

echo "═══════════════════════════════════════════════════════════════\n";
echo "  ✅ ALL TESTS PASSED!\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";
echo "Your OSS credentials are valid and have proper permissions.\n";
echo "\n";

