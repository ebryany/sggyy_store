<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'seller' => \App\Http\Middleware\IsSeller::class,
            'xendit.signature' => \App\Http\Middleware\VerifyXenditSignature::class,
            'xendit.ip' => \App\Http\Middleware\RestrictToXenditIPs::class,
            'webhook.throttle' => \App\Http\Middleware\ThrottleWebhook::class,
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
            'api/v1/webhooks/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
