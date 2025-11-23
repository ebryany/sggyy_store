<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SecurityLogger
{
    /**
     * Log unauthorized access attempts
     */
    public static function logUnauthorizedAccess(string $action, array $context = []): void
    {
        Log::channel('security')->warning('Unauthorized access attempt', [
            'action' => $action,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
            'url' => request()->fullUrl(),
            ...$context,
        ]);
    }
    
    /**
     * Log suspicious activity
     */
    public static function logSuspiciousActivity(string $activity, array $context = []): void
    {
        Log::channel('security')->warning('Suspicious activity detected', [
            'activity' => $activity,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toIso8601String(),
            'url' => request()->fullUrl(),
            ...$context,
        ]);
    }
    
    /**
     * Log security event (info level)
     */
    public static function logSecurityEvent(string $event, array $context = []): void
    {
        Log::channel('security')->info('Security event', [
            'event' => $event,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }
    
    /**
     * Log file upload security event
     */
    public static function logFileUploadEvent(string $event, array $context = []): void
    {
        Log::channel('security')->info('File upload event', [
            'event' => $event,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }
    
    /**
     * Log business logic violation
     */
    public static function logBusinessLogicViolation(string $violation, array $context = []): void
    {
        Log::channel('security')->warning('Business logic violation', [
            'violation' => $violation,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            'url' => request()->fullUrl(),
            ...$context,
        ]);
    }

    /**
     * Log financial/wallet activities (CRITICAL)
     */
    public static function logFinancialActivity(string $action, array $context = []): void
    {
        Log::channel('financial')->info($action, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            'url' => request()->fullUrl(),
            ...$context,
        ]);
    }

    /**
     * Log file access (downloads)
     */
    public static function logFileAccess(string $action, string $filePath, array $context = []): void
    {
        Log::channel('fileaccess')->info($action, [
            'file' => basename($filePath),
            'full_path' => $filePath,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }

    /**
     * Log authorization failures (for monitoring)
     */
    public static function logAuthorizationFailure(string $action, array $context = []): void
    {
        Log::channel('security')->warning('Authorization failure', [
            'action' => $action,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }

    /**
     * Log admin actions (audit trail)
     */
    public static function logAdminAction(string $action, array $context = []): void
    {
        Log::channel('admin')->info('Admin action', [
            'action' => $action,
            'admin_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }

    /**
     * Log general activity (info level)
     */
    public static function logActivity(string $activity, array $context = []): void
    {
        Log::info($activity, [
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'timestamp' => now()->toIso8601String(),
            ...$context,
        ]);
    }
}







