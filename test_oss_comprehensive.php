<?php

/**
 * Comprehensive OSS/IaaS Storage Test Script
 * 
 * Test script untuk memverifikasi konfigurasi dan koneksi OSS/IaaS
 * 
 * Usage:
 *   php test_oss_comprehensive.php
 */

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  🔧 COMPREHENSIVE OSS/IaaS STORAGE TEST - Ebrystoree\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

$results = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0,
];

/**
 * Test result helper
 */
function testResult($name, $passed, $message = '', $warning = false) {
    global $results;
    
    $icon = $passed ? '✅' : ($warning ? '⚠️' : '❌');
    $status = $passed ? 'PASS' : ($warning ? 'WARN' : 'FAIL');
    
    echo sprintf("%s [%s] %s\n", $icon, $status, $name);
    
    if ($message) {
        echo "   → {$message}\n";
    }
    
    if ($passed) {
        $results['passed']++;
    } elseif ($warning) {
        $results['warnings']++;
    } else {
        $results['failed']++;
    }
    
    echo "\n";
}

// ============================================
// TEST 1: Environment Configuration
// ============================================
echo "📋 TEST 1: Environment Configuration\n";
echo "───────────────────────────────────────────────────────────────\n";

$requiredEnvVars = [
    'FILESYSTEM_DISK' => 'Default storage disk',
    'OSS_ACCESS_KEY_ID' => 'OSS Access Key ID',
    'OSS_ACCESS_KEY_SECRET' => 'OSS Access Key Secret',
    'OSS_BUCKET' => 'OSS Bucket name',
    'OSS_ENDPOINT' => 'OSS Endpoint',
    'OSS_REGION' => 'OSS Region',
    'OSS_URL' => 'OSS URL',
];

$envConfig = [];
$allPresent = true;

foreach ($requiredEnvVars as $key => $description) {
    $value = env($key);
    $envConfig[$key] = $value;
    
    if (empty($value)) {
        testResult($description, false, "Missing: {$key}");
        $allPresent = false;
    } else {
        // Mask sensitive values
        $displayValue = in_array($key, ['OSS_ACCESS_KEY_SECRET']) 
            ? str_repeat('*', min(strlen($value), 20)) 
            : $value;
        testResult($description, true, "Value: {$displayValue}");
    }
}

if (!$allPresent) {
    echo "\n⚠️  Some environment variables are missing. Please check your .env file.\n\n";
}

// ============================================
// TEST 2: Filesystem Configuration
// ============================================
echo "📋 TEST 2: Filesystem Configuration\n";
echo "───────────────────────────────────────────────────────────────\n";

$defaultDisk = config('filesystems.default');
testResult('Default Disk', !empty($defaultDisk), "Current: {$defaultDisk}");

$ossConfig = config('filesystems.disks.oss');
if ($ossConfig) {
    testResult('OSS Disk Config', true, 'OSS disk configuration found');
    
    // Check each config value
    $configChecks = [
        'driver' => $ossConfig['driver'] ?? null,
        'key' => !empty($ossConfig['key']) ? '***' : null,
        'secret' => !empty($ossConfig['secret']) ? '***' : null,
        'bucket' => $ossConfig['bucket'] ?? null,
        'endpoint' => $ossConfig['endpoint'] ?? null,
        'region' => $ossConfig['region'] ?? null,
    ];
    
    foreach ($configChecks as $key => $value) {
        testResult("  - {$key}", !empty($value), $value ? "Value set" : "Missing");
    }
} else {
    testResult('OSS Disk Config', false, 'OSS disk configuration not found');
}

// ============================================
// TEST 3: AWS SDK Installation
// ============================================
echo "📋 TEST 3: AWS SDK Installation\n";
echo "───────────────────────────────────────────────────────────────\n";

$s3PackageInstalled = class_exists('League\Flysystem\AwsS3V3\AwsS3V3Adapter');
testResult('AWS S3 Flysystem Package', $s3PackageInstalled, 
    $s3PackageInstalled ? 'Package installed' : 'Run: composer require league/flysystem-aws-s3-v3');

// ============================================
// TEST 4: Storage Service Availability
// ============================================
echo "📋 TEST 4: Storage Service Availability\n";
echo "───────────────────────────────────────────────────────────────\n";

try {
    $storageService = app(\App\Services\StorageService::class);
    testResult('StorageService Class', true, 'Service available');
    
    $defaultDisk = $storageService->getDefaultDisk();
    testResult('Default Disk from Service', true, "Disk: {$defaultDisk}");
    
    $isConfigured = $storageService->isCloudStorageConfigured('oss');
    testResult('OSS Configuration Check', $isConfigured, 
        $isConfigured ? 'OSS is configured' : 'OSS configuration incomplete');
} catch (\Exception $e) {
    testResult('StorageService Class', false, "Error: {$e->getMessage()}");
}

// ============================================
// TEST 5: OSS Connection Test
// ============================================
echo "📋 TEST 5: OSS Connection Test\n";
echo "───────────────────────────────────────────────────────────────\n";

try {
    $ossDisk = Storage::disk('oss');
    testResult('OSS Disk Instance', true, 'OSS disk created successfully');
    
    // Test 5.1: List files (test connection)
    try {
        $files = $ossDisk->files('', true);
        testResult('  - List Files', true, "Found " . count($files) . " files");
    } catch (\Exception $e) {
        testResult('  - List Files', false, "Error: {$e->getMessage()}");
    }
    
} catch (\Exception $e) {
    testResult('OSS Disk Instance', false, "Error: {$e->getMessage()}");
}

// ============================================
// TEST 6: Write Operation Test
// ============================================
echo "📋 TEST 6: Write Operation Test\n";
echo "───────────────────────────────────────────────────────────────\n";

$testFileName = 'test/oss-test-' . date('Y-m-d-H-i-s') . '.txt';
$testContent = "OSS Test File\nCreated at: " . now()->toDateTimeString() . "\n";

try {
    $ossDisk = Storage::disk('oss');
    
    // Write test file
    $written = $ossDisk->put($testFileName, $testContent);
    testResult('Write File', $written, "File: {$testFileName}");
    
    if ($written) {
        // Test 6.1: Check if file exists
        $exists = $ossDisk->exists($testFileName);
        testResult('  - File Exists', $exists, $exists ? 'File found' : 'File not found');
        
        // Test 6.2: Read file
        try {
            $readContent = $ossDisk->get($testFileName);
            $readSuccess = $readContent === $testContent;
            testResult('  - Read File', $readSuccess, 
                $readSuccess ? 'Content matches' : 'Content mismatch');
        } catch (\Exception $e) {
            testResult('  - Read File', false, "Error: {$e->getMessage()}");
        }
        
        // Test 6.3: Get file URL
        try {
            $url = $ossDisk->url($testFileName);
            testResult('  - Get URL', !empty($url), "URL: {$url}");
        } catch (\Exception $e) {
            testResult('  - Get URL', false, "Error: {$e->getMessage()}");
        }
        
        // Test 6.4: Get file size
        try {
            $size = $ossDisk->size($testFileName);
            testResult('  - Get File Size', $size > 0, "Size: {$size} bytes");
        } catch (\Exception $e) {
            testResult('  - Get File Size', false, "Error: {$e->getMessage()}");
        }
        
        // Test 6.5: Delete test file
        try {
            $deleted = $ossDisk->delete($testFileName);
            testResult('  - Delete File', $deleted, $deleted ? 'File deleted' : 'Delete failed');
        } catch (\Exception $e) {
            testResult('  - Delete File', false, "Error: {$e->getMessage()}");
        }
    }
    
} catch (\Exception $e) {
    testResult('Write File', false, "Error: {$e->getMessage()}");
}

// ============================================
// TEST 7: URL Generation Test
// ============================================
echo "📋 TEST 7: URL Generation Test\n";
echo "───────────────────────────────────────────────────────────────\n";

try {
    $ossDisk = Storage::disk('oss');
    $testPath = 'test/sample-file.txt';
    
    // Test 7.1: Generate URL
    try {
        $url = $ossDisk->url($testPath);
        $isValidUrl = filter_var($url, FILTER_VALIDATE_URL) !== false;
        testResult('Generate URL', $isValidUrl, "URL: {$url}");
    } catch (\Exception $e) {
        testResult('Generate URL', false, "Error: {$e->getMessage()}");
    }
    
    // Test 7.2: Generate Temporary URL (if supported)
    try {
        $tempUrl = $ossDisk->temporaryUrl($testPath, now()->addMinutes(60));
        $isValidTempUrl = filter_var($tempUrl, FILTER_VALIDATE_URL) !== false;
        testResult('Generate Temporary URL', $isValidTempUrl, 
            $isValidTempUrl ? 'Temporary URL generated' : 'Invalid temporary URL');
    } catch (\Exception $e) {
        testResult('Generate Temporary URL', false, 
            "Not supported or error: {$e->getMessage()}", true);
    }
    
} catch (\Exception $e) {
    testResult('URL Generation', false, "Error: {$e->getMessage()}");
}

// ============================================
// TEST 8: StorageService Integration Test
// ============================================
echo "📋 TEST 8: StorageService Integration Test\n";
echo "───────────────────────────────────────────────────────────────\n";

try {
    $storageService = app(\App\Services\StorageService::class);
    
    // Test 8.1: Check configuration
    $isConfigured = $storageService->isCloudStorageConfigured('oss');
    testResult('StorageService Config Check', $isConfigured, 
        $isConfigured ? 'OSS configured' : 'OSS not configured');
    
    // Test 8.2: Get default disk
    $defaultDisk = $storageService->getDefaultDisk();
    testResult('Get Default Disk', !empty($defaultDisk), "Default: {$defaultDisk}");
    
} catch (\Exception $e) {
    testResult('StorageService Integration', false, "Error: {$e->getMessage()}");
}

// ============================================
// TEST 9: Network Connectivity Test
// ============================================
echo "📋 TEST 9: Network Connectivity Test\n";
echo "───────────────────────────────────────────────────────────────\n";

$endpoint = env('OSS_ENDPOINT');
if ($endpoint) {
    // Extract hostname from endpoint
    $hostname = str_replace(['http://', 'https://'], '', $endpoint);
    $hostname = explode('/', $hostname)[0];
    
    // Test DNS resolution
    $ip = gethostbyname($hostname);
    $dnsResolved = $ip !== $hostname;
    testResult('DNS Resolution', $dnsResolved, 
        $dnsResolved ? "Resolved to: {$ip}" : "Cannot resolve: {$hostname}");
    
    // Test port connectivity (if possible)
    if ($dnsResolved) {
        $port = 443; // HTTPS
        $connection = @fsockopen($hostname, $port, $errno, $errstr, 5);
        $portOpen = $connection !== false;
        testResult('Port Connectivity (443)', $portOpen, 
            $portOpen ? "Port {$port} is open" : "Port {$port} is closed or unreachable");
        if ($connection) {
            fclose($connection);
        }
    }
} else {
    testResult('Network Connectivity', false, 'OSS_ENDPOINT not configured');
}

// ============================================
// SUMMARY
// ============================================
echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  📊 TEST SUMMARY\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

$total = $results['passed'] + $results['failed'] + $results['warnings'];
$passRate = $total > 0 ? round(($results['passed'] / $total) * 100, 2) : 0;

echo "Total Tests: {$total}\n";
echo "✅ Passed: {$results['passed']}\n";
echo "⚠️  Warnings: {$results['warnings']}\n";
echo "❌ Failed: {$results['failed']}\n";
echo "📈 Pass Rate: {$passRate}%\n";
echo "\n";

if ($results['failed'] === 0 && $results['warnings'] === 0) {
    echo "🎉 All tests passed! OSS/IaaS storage is configured correctly.\n";
} elseif ($results['failed'] === 0) {
    echo "✅ All critical tests passed! Some warnings detected.\n";
} else {
    echo "❌ Some tests failed. Please check the errors above.\n";
    echo "\n";
    echo "💡 Troubleshooting Tips:\n";
    echo "   1. Check your .env file for OSS configuration\n";
    echo "   2. Run: php artisan config:clear\n";
    echo "   3. Verify OSS credentials are correct\n";
    echo "   4. Check network connectivity to OSS endpoint\n";
    echo "   5. See OSS_TROUBLESHOOTING.md for more help\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n";

