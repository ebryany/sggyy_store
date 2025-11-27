<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Filesystem\OssAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;

class FilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register OSS disk dengan custom adapter (tanpa AWS SDK)
        Storage::extend('oss', function ($app, $config) {
            $adapter = new OssAdapter([
                'key' => $config['key'] ?? env('OSS_ACCESS_KEY_ID'),
                'secret' => $config['secret'] ?? env('OSS_ACCESS_KEY_SECRET'),
                'endpoint' => $config['endpoint'] ?? env('OSS_ENDPOINT'),
                'bucket' => $config['bucket'] ?? env('OSS_BUCKET'),
                'url' => $config['url'] ?? env('OSS_URL'),
            ]);

            // Create Flysystem instance
            $flysystem = new Filesystem($adapter, $config);
            
            // Wrap with Laravel FilesystemAdapter
            // Laravel FilesystemAdapter automatically uses publicUrl() from adapter for url() method
            $filesystemAdapter = new FilesystemAdapter($flysystem, $adapter, $config);
            
            return $filesystemAdapter;
        });
    }
}

