<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\AdminDashboardService;
use App\Services\SettingsService;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all application cache including config, routes, views, and service caches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Clearing all caches...');
        $this->newLine();

        // Clear Laravel caches
        $this->info('📦 Clearing Laravel caches...');
        Artisan::call('cache:clear');
        $this->info('  ✅ Application cache cleared');

        Artisan::call('config:clear');
        $this->info('  ✅ Config cache cleared');

        Artisan::call('route:clear');
        $this->info('  ✅ Route cache cleared');

        Artisan::call('view:clear');
        $this->info('  ✅ View cache cleared');

        Artisan::call('event:clear');
        $this->info('  ✅ Event cache cleared');

        // Clear service caches
        $this->newLine();
        $this->info('🔧 Clearing service caches...');
        
        try {
            $adminDashboardService = app(AdminDashboardService::class);
            $adminDashboardService->clearCache();
            $this->info('  ✅ Admin dashboard cache cleared');
        } catch (\Exception $e) {
            $this->warn('  ⚠️  Failed to clear admin dashboard cache: ' . $e->getMessage());
        }

        try {
            $settingsService = app(SettingsService::class);
            $settingsService->clearCache();
            $this->info('  ✅ Settings cache cleared');
        } catch (\Exception $e) {
            $this->warn('  ⚠️  Failed to clear settings cache: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('✨ All caches cleared successfully!');
        
        return 0;
    }
}

