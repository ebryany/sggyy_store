<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use OSS\OssClient;
use OSS\Core\OssException;

class FixOssFileAcl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oss:fix-acl 
                            {path? : Specific file path to fix (optional)}
                            {--prefix= : Fix all files with this prefix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix OSS file ACL to public-read for existing files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Fixing OSS file ACL to public-read...');
        $this->newLine();

        $path = $this->argument('path');
        $prefix = $this->option('prefix');

        try {
            $ossDisk = Storage::disk('oss');
            $adapter = $ossDisk->getAdapter();
            
            if (!($adapter instanceof \App\Filesystem\OssAdapter)) {
                $this->error('❌ OSS adapter not found!');
                return 1;
            }

            // Get OSS client from adapter using reflection
            $reflection = new \ReflectionClass($adapter);
            $clientProperty = $reflection->getProperty('client');
            $clientProperty->setAccessible(true);
            $client = $clientProperty->getValue($adapter);
            
            $bucketProperty = $reflection->getProperty('bucket');
            $bucketProperty->setAccessible(true);
            $bucket = $bucketProperty->getValue($adapter);

            if ($path) {
                // Fix specific file
                $this->fixFileAcl($client, $bucket, $path);
            } elseif ($prefix) {
                // Fix all files with prefix
                $this->fixFilesByPrefix($client, $bucket, $prefix);
            } else {
                // Fix common paths
                $this->info('Fixing common file paths...');
                $commonPaths = [
                    'settings/favicon',
                    'settings/logo',
                    'settings/banners',
                ];
                
                foreach ($commonPaths as $prefix) {
                    $this->fixFilesByPrefix($client, $bucket, $prefix);
                }
            }

            $this->newLine();
            $this->info('✅ ACL fix completed!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }
    }

    private function fixFileAcl(OssClient $client, string $bucket, string $path): void
    {
        try {
            // Check if file exists
            if (!$client->doesObjectExist($bucket, $path)) {
                $this->warn("   ⚠️  File not found: {$path}");
                return;
            }

            // Set ACL to public-read
            $client->putObjectAcl($bucket, $path, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
            $this->info("   ✅ Fixed: {$path}");
        } catch (OssException $e) {
            $this->error("   ❌ Failed to fix {$path}: " . $e->getMessage());
        }
    }

    private function fixFilesByPrefix(OssClient $client, string $bucket, string $prefix): void
    {
        $this->info("📁 Fixing files with prefix: {$prefix}");
        
        try {
            $result = $client->listObjects($bucket, [
                'prefix' => $prefix,
                'max-keys' => 1000,
            ]);

            $objects = $result->getObjectList();
            $count = count($objects);
            
            if ($count === 0) {
                $this->warn("   ⚠️  No files found with prefix: {$prefix}");
                return;
            }

            $this->info("   Found {$count} file(s)");
            
            $bar = $this->output->createProgressBar($count);
            $bar->start();

            $fixed = 0;
            $failed = 0;

            foreach ($objects as $object) {
                $path = $object->getKey();
                try {
                    $client->putObjectAcl($bucket, $path, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
                    $fixed++;
                } catch (OssException $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("   ❌ Failed: {$path} - " . $e->getMessage());
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            $this->info("   ✅ Fixed: {$fixed}, ❌ Failed: {$failed}");
        } catch (OssException $e) {
            $this->error("   ❌ Error listing files: " . $e->getMessage());
        }
    }
}

