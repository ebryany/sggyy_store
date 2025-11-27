<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🌐 OSS Network Connectivity Check\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$endpoint = env('OSS_ENDPOINT');
$bucket = env('OSS_BUCKET');

if (!$endpoint) {
    echo "❌ OSS_ENDPOINT not set!\n";
    exit(1);
}

// Extract domain from endpoint
$domain = str_replace(['http://', 'https://'], '', $endpoint);
$testUrl = "https://{$bucket}.{$domain}";

echo "📋 Network Test Details:\n";
echo "   Endpoint: {$endpoint}\n";
echo "   Bucket: {$bucket}\n";
echo "   Test URL: {$testUrl}\n\n";

// Test 1: DNS Resolution
echo "🔧 Test 1: DNS Resolution\n";
echo "───────────────────────────────────────────────────────────────\n";
$ip = gethostbyname($domain);
if ($ip === $domain) {
    echo "   ❌ DNS resolution failed for: {$domain}\n";
} else {
    echo "   ✅ DNS resolved: {$domain} → {$ip}\n";
}

echo "\n";

// Test 2: HTTP Connectivity (using file_get_contents with context)
echo "🔧 Test 2: HTTP Connectivity Test\n";
echo "───────────────────────────────────────────────────────────────\n";
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'HEAD',
        'ignore_errors' => true,
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
    ],
]);

try {
    $startTime = microtime(true);
    $result = @file_get_contents($testUrl, false, $context);
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);
    
    if ($result !== false) {
        echo "   ✅ Connection successful (took {$duration}ms)\n";
    } else {
        $error = error_get_last();
        echo "   ⚠️  Connection attempt made (took {$duration}ms)\n";
        if ($error) {
            echo "   Error: " . $error['message'] . "\n";
        }
    }
} catch (\Exception $e) {
    echo "   ❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: cURL Test (what OSS SDK uses internally)
echo "🔧 Test 3: cURL Connectivity Test\n";
echo "───────────────────────────────────────────────────────────────\n";
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $testUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_NOBODY => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 3,
]);

$startTime = microtime(true);
$response = curl_exec($ch);
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);

if ($response === false) {
    $error = curl_error($ch);
    $errno = curl_errno($ch);
    echo "   ❌ cURL failed\n";
    echo "   Error: {$error} (Code: {$errno})\n";
    echo "   Duration: {$duration}ms\n";
    
    // Common cURL error codes
    $errorMessages = [
        6 => "Couldn't resolve host (DNS issue)",
        7 => "Failed to connect to host (Network/Firewall)",
        28 => "Operation timeout",
        35 => "SSL connect error",
        56 => "Recv failure: Connection reset",
        55 => "Send failure: Connection reset",
    ];
    
    if (isset($errorMessages[$errno])) {
        echo "   💡 " . $errorMessages[$errno] . "\n";
    }
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "   ✅ cURL connection successful\n";
    echo "   HTTP Code: {$httpCode}\n";
    echo "   Duration: {$duration}ms\n";
}

curl_close($ch);

echo "\n";

// Test 4: Check Windows Firewall / Antivirus
echo "🔧 Test 4: System Check\n";
echo "───────────────────────────────────────────────────────────────\n";
if (PHP_OS_FAMILY === 'Windows') {
    echo "   OS: Windows\n";
    echo "   ⚠️  Windows Firewall or Antivirus might be blocking outbound connections\n";
    echo "   💡 Try:\n";
    echo "      1. Temporarily disable Windows Firewall\n";
    echo "      2. Add exception for PHP/Composer in Antivirus\n";
    echo "      3. Check if proxy is required\n";
} else {
    echo "   OS: " . PHP_OS . "\n";
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "\n💡 Recommendations:\n";
echo "   1. Check Windows Firewall settings\n";
echo "   2. Check Antivirus software (might block cURL)\n";
echo "   3. Try from different network (test if it's network-specific)\n";
echo "   4. Check if proxy is needed (corporate network)\n";
echo "   5. Verify OSS endpoint is accessible from your location\n";
echo "\n";

