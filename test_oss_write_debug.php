<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$disk = \Illuminate\Support\Facades\Storage::disk('oss');

$testFileName = 'test/debug-write-' . time() . '.txt';
$testContent = 'Test content for OSS write';

echo "Testing OSS write operation...\n";
echo "File: {$testFileName}\n";
echo "Content: {$testContent}\n\n";

try {
    echo "Calling put()...\n";
    
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Try to catch any silent errors
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        echo "PHP Error: [{$errno}] {$errstr} in {$errfile} on line {$errline}\n";
    });
    
    $result = $disk->put($testFileName, $testContent);
    
    restore_error_handler();
    
    echo "Result: " . var_export($result, true) . "\n";
    echo "Type: " . gettype($result) . "\n";
    echo "Is false? " . ($result === false ? 'YES' : 'NO') . "\n";
    echo "Is empty? " . (empty($result) ? 'YES' : 'NO') . "\n";
    
    if ($result !== false) {
        echo "\nChecking if file exists...\n";
        $exists = $disk->exists($testFileName);
        echo "Exists: " . ($exists ? 'YES' : 'NO') . "\n";
        
        if ($exists) {
            echo "\nReading file...\n";
            $readContent = $disk->get($testFileName);
            echo "Read content: {$readContent}\n";
            echo "Content matches: " . ($readContent === $testContent ? 'YES' : 'NO') . "\n";
        }
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Class: " . get_class($e) . "\n";
    if ($e->getPrevious()) {
        echo "Previous: " . $e->getPrevious()->getMessage() . "\n";
    }
}
