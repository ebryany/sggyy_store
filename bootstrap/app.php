<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'seller' => \App\Http\Middleware\IsSeller::class,
        ]);
        
        // Trust proxies for load balancer support
        $middleware->trustProxies(at: '*');
        
        // Rate limiting
        $middleware->throttleApi();
        
        // Exclude webhook routes from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            'webhooks/xendit',
            'webhooks/xendit/*',
            'quota.webhook',
            'quota.webhook.get',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
