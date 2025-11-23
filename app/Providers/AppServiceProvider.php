<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom route model binding for Product: support both slug and ID
        \Illuminate\Support\Facades\Route::bind('product', function ($value) {
            return \App\Models\Product::findBySlugOrId($value) ?? abort(404);
        });
        
        // Custom route model binding for Service: support both slug and ID
        \Illuminate\Support\Facades\Route::bind('service', function ($value) {
            return \App\Models\Service::findBySlugOrId($value) ?? abort(404);
        });
        
        // Custom route model binding for Order: use order_number instead of ID
        \Illuminate\Support\Facades\Route::bind('order', function ($value) {
            return \App\Models\Order::findByOrderNumberOrId($value) ?? abort(404);
        });
    }
}
