<?php

namespace App\Console\Commands;

use App\Services\StorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Exception;

class TestStorageConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:test {disk? : Disk name to test (default: from config)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test storage connection (OSS/S3/Local)';

    /**
     * Execute the console command.
     */
    public function handle(StorageService $storageService): int
    {
        $disk = $this->argument('disk') ?? config('filesystems.default', 'public');

        $this->info("🔍 Testing storage connection for disk: {$disk}");
        $this->newLine();

        // Check configuration
        $this->info("📋 Checking configuration...");
        
        if ($disk === 'oss') {
            $this->checkOssConfig();
        } elseif ($disk === 's3') {
            $this->checkS3Config();
        } else {
            $this->info("✅ Local disk '{$disk}' - no cloud configuration needed");
        }

        $this->newLine();

        // Test connection
        $this->info("🔌 Testing connection...");
        
        try {
            if ($storageService->isCloudStorageConfigured($disk)) {
                $this->info("✅ Configuration valid");
            } else {
                $this->error("❌ Configuration invalid or missing");
                return Command::FAILURE;
            }
        } catch (Exception $e) {
            $this->error("❌ Connection test failed: " . $e->getMessage());
            return Command::FAILURE;
        }

        $this->newLine();

        // Test write
        $this->info("✍️  Testing write operation...");
        $testFile = 'test-connection-' . time() . '.txt';
        $testContent = 'Storage connection test - ' . now()->toDateTimeString();

        try {
            Storage::disk($disk)->put($testFile, $testContent);
            $this->info("✅ Write successful: {$testFile}");
        } catch (Exception $e) {
            $this->error("❌ Write failed: " . $e->getMessage());
            return Command::FAILURE;
        }

        // Test read
        $this->info("📖 Testing read operation...");
        try {
            $content = Storage::disk($disk)->get($testFile);
            if ($content === $testContent) {
                $this->info("✅ Read successful");
            } else {
                $this->warn("⚠️  Read successful but content mismatch");
            }
        } catch (Exception $e) {
            $this->error("❌ Read failed: " . $e->getMessage());
            return Command::FAILURE;
        }

        // Test URL generation
        $this->info("🔗 Testing URL generation...");
        try {
            $url = Storage::disk($disk)->url($testFile);
            $this->info("✅ URL generated: {$url}");
        } catch (Exception $e) {
            $this->warn("⚠️  URL generation failed: " . $e->getMessage());
        }

        // Cleanup
        $this->info("🧹 Cleaning up test file...");
        try {
            Storage::disk($disk)->delete($testFile);
            $this->info("✅ Test file deleted");
        } catch (Exception $e) {
            $this->warn("⚠️  Could not delete test file: " . $e->getMessage());
        }

        $this->newLine();
        $this->info("✅ All tests passed! Storage connection is working.");
        
        return Command::SUCCESS;
    }

    private function checkOssConfig(): void
    {
        $required = [
            'OSS_ACCESS_KEY_ID' => env('OSS_ACCESS_KEY_ID'),
            'OSS_ACCESS_KEY_SECRET' => env('OSS_ACCESS_KEY_SECRET'),
            'OSS_BUCKET' => env('OSS_BUCKET'),
            'OSS_ENDPOINT' => env('OSS_ENDPOINT'),
            'OSS_REGION' => env('OSS_REGION', 'ap-southeast-1'),
            'OSS_URL' => env('OSS_URL'),
        ];

        $this->table(
            ['Config Key', 'Value', 'Status'],
            collect($required)->map(function ($value, $key) {
                $status = !empty($value) ? '✅ Set' : '❌ Missing';
                $displayValue = $key === 'OSS_ACCESS_KEY_SECRET' 
                    ? (empty($value) ? '' : str_repeat('*', 8))
                    : ($value ?? '');
                return [$key, $displayValue, $status];
            })->toArray()
        );

        $missing = collect($required)->filter(fn($value) => empty($value))->keys();
        if ($missing->isNotEmpty()) {
            $this->warn("⚠️  Missing configuration: " . $missing->join(', '));
        }
    }

    private function checkS3Config(): void
    {
        $required = [
            'AWS_ACCESS_KEY_ID' => env('AWS_ACCESS_KEY_ID'),
            'AWS_SECRET_ACCESS_KEY' => env('AWS_SECRET_ACCESS_KEY'),
            'AWS_BUCKET' => env('AWS_BUCKET'),
            'AWS_DEFAULT_REGION' => env('AWS_DEFAULT_REGION'),
            'AWS_URL' => env('AWS_URL'),
            'AWS_ENDPOINT' => env('AWS_ENDPOINT'),
        ];

        $this->table(
            ['Config Key', 'Value', 'Status'],
            collect($required)->map(function ($value, $key) {
                $status = !empty($value) ? '✅ Set' : '❌ Missing';
                $displayValue = $key === 'AWS_SECRET_ACCESS_KEY' 
                    ? (empty($value) ? '' : str_repeat('*', 8))
                    : ($value ?? '');
                return [$key, $displayValue, $status];
            })->toArray()
        );

        $missing = collect($required)->filter(fn($value) => empty($value))->keys();
        if ($missing->isNotEmpty()) {
            $this->warn("⚠️  Missing configuration: " . $missing->join(', '));
        }
    }
}

