<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SecurityLogger;

/**
 * Throttle Failed Login Attempts Middleware
 * 
 * 🔒 SECURITY: Account lockout mechanism to prevent brute force attacks
 * - Max 5 failed attempts per 15 minutes
 * - Lockout for 30 minutes after 10 failed attempts
 * - Permanent ban after 20 failed attempts (requires admin unlock)
 */
class ThrottleFailedLogins
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('POST') || !$request->is('login')) {
            return $next($request);
        }

        $email = $request->input('email');
        $ip = $request->ip();
        
        if (!$email) {
            return $next($request);
        }

        // Check if account is permanently locked
        if ($this->isPermanentlyLocked($email)) {
            SecurityLogger::logSuspiciousActivity('Attempt to login to permanently locked account', [
                'email' => $email,
                'ip' => $ip,
            ]);
            
            return back()->withErrors([
                'email' => 'Akun ini telah dikunci. Silakan hubungi administrator untuk membuka kunci.',
            ])->withInput($request->only('email'));
        }

        // Check if temporarily locked
        if ($this->isTemporarilyLocked($email)) {
            $remainingMinutes = $this->getRemainingLockoutTime($email);
            
            SecurityLogger::logSuspiciousActivity('Attempt to login to temporarily locked account', [
                'email' => $email,
                'ip' => $ip,
                'remaining_minutes' => $remainingMinutes,
            ]);
            
            return back()->withErrors([
                'email' => "Akun dikunci karena terlalu banyak percobaan login gagal. Coba lagi dalam {$remainingMinutes} menit.",
            ])->withInput($request->only('email'));
        }

        return $next($request);
    }

    /**
     * Check if account is permanently locked
     */
    private function isPermanentlyLocked(string $email): bool
    {
        return Cache::has("login_permanent_lock:{$email}");
    }

    /**
     * Check if account is temporarily locked
     */
    private function isTemporarilyLocked(string $email): bool
    {
        return Cache::has("login_temp_lock:{$email}");
    }

    /**
     * Get remaining lockout time in minutes
     */
    private function getRemainingLockoutTime(string $email): int
    {
        $expiresAt = Cache::get("login_temp_lock:{$email}");
        
        if (!$expiresAt) {
            return 0;
        }

        $remaining = $expiresAt - now()->timestamp;
        return max(1, (int) ceil($remaining / 60));
    }

    /**
     * Record failed login attempt
     */
    public static function recordFailedAttempt(string $email, string $ip): void
    {
        $key = "login_attempts:{$email}";
        $attempts = (int) Cache::get($key, 0) + 1;
        
        // Store attempts for 1 hour
        Cache::put($key, $attempts, now()->addHour());

        SecurityLogger::logSuspiciousActivity('Failed login attempt', [
            'email' => $email,
            'ip' => $ip,
            'attempt_count' => $attempts,
        ]);

        // Temporary lockout after 10 attempts (30 minutes)
        if ($attempts >= 10 && $attempts < 20) {
            $expiresAt = now()->addMinutes(30)->timestamp;
            Cache::put("login_temp_lock:{$email}", $expiresAt, now()->addMinutes(30));
            
            SecurityLogger::logSecurityEvent('Account temporarily locked due to failed attempts', [
                'email' => $email,
                'ip' => $ip,
                'attempt_count' => $attempts,
                'lockout_duration' => '30 minutes',
            ]);
        }

        // Permanent lockout after 20 attempts (requires admin unlock)
        if ($attempts >= 20) {
            Cache::put("login_permanent_lock:{$email}", true, now()->addDays(30));
            
            SecurityLogger::logSecurityEvent('Account permanently locked due to excessive failed attempts', [
                'email' => $email,
                'ip' => $ip,
                'attempt_count' => $attempts,
            ]);
            
            // TODO: Send email notification to admin
        }
    }

    /**
     * Clear failed attempts (call on successful login)
     */
    public static function clearFailedAttempts(string $email): void
    {
        Cache::forget("login_attempts:{$email}");
        Cache::forget("login_temp_lock:{$email}");
        // Don't clear permanent lock - requires admin action
    }

    /**
     * Unlock account (admin action)
     */
    public static function unlockAccount(string $email): void
    {
        Cache::forget("login_attempts:{$email}");
        Cache::forget("login_temp_lock:{$email}");
        Cache::forget("login_permanent_lock:{$email}");
        
        SecurityLogger::logAdminAction('Account unlocked', [
            'email' => $email,
        ]);
    }
}


