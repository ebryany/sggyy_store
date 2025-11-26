<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

/**
 * Restrict to Xendit IPs
 * 
 * Production-grade security middleware untuk membatasi webhook
 * hanya dari IP Xendit yang valid
 * 
 * Xendit IP Ranges (as of 2024):
 * - 147.139.162.0/24
 * - 103.10.128.0/22
 * - 52.221.194.0/24 (Singapore)
 * 
 * Note: IP ranges bisa berubah, check Xendit documentation
 * 
 * Usage:
 * Route::post('/webhooks/xendit/*', ...)->middleware('xendit.ip');
 */
class RestrictToXenditIPs
{
    /**
     * Xendit IP ranges (CIDR notation)
     * 
     * @var array
     */
    protected array $allowedIpRanges = [
        '147.139.162.0/24',   // Xendit primary
        '103.10.128.0/22',    // Xendit secondary
        '52.221.194.0/24',    // Xendit Singapore
        '127.0.0.1',          // Localhost for development
        '::1',                // IPv6 localhost for development
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip in local/development environment
        if (app()->environment(['local', 'development', 'testing'])) {
            return $next($request);
        }

        $clientIp = $request->ip();

        // Check if IP is allowed
        if (!$this->isIpAllowed($clientIp)) {
            Log::warning('Xendit webhook: IP not allowed', [
                'ip' => $clientIp,
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden',
            ], 403);
        }

        Log::info('Xendit webhook: IP verified', [
            'ip' => $clientIp,
            'path' => $request->path(),
        ]);

        return $next($request);
    }

    /**
     * Check if IP is in allowed ranges
     * 
     * @param string $ip
     * @return bool
     */
    protected function isIpAllowed(string $ip): bool
    {
        foreach ($this->allowedIpRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if IP is in CIDR range
     * 
     * @param string $ip
     * @param string $range CIDR notation (e.g., 192.168.1.0/24)
     * @return bool
     */
    protected function ipInRange(string $ip, string $range): bool
    {
        // If range is a single IP (no CIDR)
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        // Parse CIDR
        list($subnet, $mask) = explode('/', $range);
        
        // Convert IP to long
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        
        if ($ipLong === false || $subnetLong === false) {
            return false;
        }
        
        // Calculate mask
        $maskLong = -1 << (32 - (int) $mask);
        
        // Check if IP is in subnet
        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }

    /**
     * Get allowed IP ranges (for configuration/debugging)
     * 
     * @return array
     */
    public function getAllowedIpRanges(): array
    {
        return $this->allowedIpRanges;
    }

    /**
     * Add custom IP range (for dynamic configuration)
     * 
     * @param string $ipRange
     * @return void
     */
    public function addAllowedIpRange(string $ipRange): void
    {
        if (!in_array($ipRange, $this->allowedIpRanges)) {
            $this->allowedIpRanges[] = $ipRange;
        }
    }
}

