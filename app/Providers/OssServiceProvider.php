<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Filesystem\OssAdapter;
use League\Flysystem\Filesystem;

class OssServiceProvider extends ServiceProvider
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
        // Register custom OSS adapter
        Storage::extend('oss', function ($app, $config) {
            $adapter = new OssAdapter($config);
            return new Filesystem($adapter);
        });
    }
}

