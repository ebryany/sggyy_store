<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

/**
 * Throttle Webhook Middleware
 * 
 * Rate limiting khusus untuk webhook endpoints
 * Melindungi dari spam, DDoS, dan excessive retries
 * 
 * Limits:
 * - Per IP: 60 requests per minute (normal traffic)
 * - Global: 1000 requests per minute (burst protection)
 * - Per endpoint: 100 requests per minute per endpoint
 * 
 * Usage:
 * Route::post('/webhooks/*', ...)->middleware('webhook.throttle');
 */
class ThrottleWebhook
{
    /**
     * Rate limits configuration
     */
    protected const LIMIT_PER_IP = 60;           // Per IP per minute
    protected const LIMIT_PER_ENDPOINT = 100;    // Per endpoint per minute
    protected const LIMIT_GLOBAL = 1000;         // Global per minute
    protected const DECAY_MINUTES = 1;           // Time window

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $endpoint = $request->path();

        // Check multiple rate limits
        $limiters = [
            'webhook:ip:' . $ip => self::LIMIT_PER_IP,
            'webhook:endpoint:' . $endpoint => self::LIMIT_PER_ENDPOINT,
            'webhook:global' => self::LIMIT_GLOBAL,
        ];

        foreach ($limiters as $key => $maxAttempts) {
            if ($this->tooManyAttempts($key, $maxAttempts)) {
                Log::warning('Webhook rate limit exceeded', [
                    'limiter' => $key,
                    'ip' => $ip,
                    'endpoint' => $endpoint,
                    'user_agent' => $request->userAgent(),
                ]);

                return $this->buildRateLimitResponse($key, $maxAttempts);
            }

            // Increment hit
            RateLimiter::hit($key, self::DECAY_MINUTES * 60);
        }

        // Continue with request
        $response = $next($request);

        // Add rate limit headers to response
        return $this->addRateLimitHeaders($response, 'webhook:ip:' . $ip, self::LIMIT_PER_IP);
    }

    /**
     * Check if rate limit exceeded
     */
    protected function tooManyAttempts(string $key, int $maxAttempts): bool
    {
        return RateLimiter::tooManyAttempts($key, $maxAttempts);
    }

    /**
     * Build rate limit exceeded response
     */
    protected function buildRateLimitResponse(string $key, int $maxAttempts): Response
    {
        $retryAfter = RateLimiter::availableIn($key);

        return response()->json([
            'status' => 'error',
            'message' => 'Too many requests',
            'retry_after' => $retryAfter,
        ], 429)
        ->header('Retry-After', $retryAfter)
        ->header('X-RateLimit-Limit', $maxAttempts)
        ->header('X-RateLimit-Remaining', 0);
    }

    /**
     * Add rate limit headers to response
     */
    protected function addRateLimitHeaders(Response $response, string $key, int $maxAttempts): Response
    {
        $remaining = max(0, $maxAttempts - RateLimiter::attempts($key));
        $retryAfter = RateLimiter::availableIn($key);

        return $response
            ->header('X-RateLimit-Limit', $maxAttempts)
            ->header('X-RateLimit-Remaining', $remaining)
            ->header('X-RateLimit-Reset', now()->addSeconds($retryAfter)->timestamp);
    }

    /**
     * Get current rate limit status (for debugging/monitoring)
     */
    public function getStatus(string $ip, string $endpoint): array
    {
        $keys = [
            'per_ip' => 'webhook:ip:' . $ip,
            'per_endpoint' => 'webhook:endpoint:' . $endpoint,
            'global' => 'webhook:global',
        ];

        $status = [];
        foreach ($keys as $type => $key) {
            $attempts = RateLimiter::attempts($key);
            $limit = match($type) {
                'per_ip' => self::LIMIT_PER_IP,
                'per_endpoint' => self::LIMIT_PER_ENDPOINT,
                'global' => self::LIMIT_GLOBAL,
            };

            $status[$type] = [
                'attempts' => $attempts,
                'limit' => $limit,
                'remaining' => max(0, $limit - $attempts),
                'exceeded' => $attempts >= $limit,
                'retry_after' => $attempts >= $limit ? RateLimiter::availableIn($key) : 0,
            ];
        }

        return $status;
    }

    /**
     * Clear rate limit for specific key (for testing/admin)
     */
    public function clearLimit(string $key): void
    {
        RateLimiter::clear($key);
    }

    /**
     * Clear all webhook rate limits (for testing/admin)
     */
    public function clearAllLimits(string $ip, string $endpoint): void
    {
        RateLimiter::clear('webhook:ip:' . $ip);
        RateLimiter::clear('webhook:endpoint:' . $endpoint);
        RateLimiter::clear('webhook:global');
    }
}

