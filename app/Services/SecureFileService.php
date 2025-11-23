<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

/**
 * Secure File Service
 * 
 * Provides secure file download functionality with path traversal protection
 */
class SecureFileService
{
    /**
     * Securely download file with path traversal protection
     * 
     * @param string $userProvidedPath User-supplied path or filename
     * @param string $allowedDirectory Allowed storage directory (e.g., 'app/private/deliverables')
     * @param string $disk Storage disk ('public' or 'private')
     * @return Response
     * @throws \Exception
     */
    public function secureDownload(string $userProvidedPath, string $allowedDirectory, string $disk = 'public'): Response
    {
        // 1. Extract basename only (removes any directory traversal attempts)
        $filename = basename($userProvidedPath);
        
        // 2. Construct full path within allowed directory
        $relativePath = trim($allowedDirectory, '/') . '/' . $filename;
        
        // 3. Check if file exists in storage
        if (!Storage::disk($disk)->exists($relativePath)) {
            SecurityLogger::logSuspiciousActivity('File download attempt - file not found', [
                'user_id' => auth()->id(),
                'requested_path' => $userProvidedPath,
                'resolved_path' => $relativePath,
                'disk' => $disk,
            ]);
            abort(404, 'File tidak ditemukan');
        }
        
        // 4. Get real path from storage
        $fullPath = Storage::disk($disk)->path($relativePath);
        $realPath = realpath($fullPath);
        
        // 5. Ensure file exists (double check)
        if (!$realPath || !file_exists($realPath)) {
            SecurityLogger::logSuspiciousActivity('File download attempt - real path not found', [
                'user_id' => auth()->id(),
                'full_path' => $fullPath,
                'real_path' => $realPath,
            ]);
            abort(404, 'File tidak ditemukan');
        }
        
        // 6. Get allowed directory real path
        $allowedRealPath = realpath(Storage::disk($disk)->path($allowedDirectory));
        
        // 7. Ensure file is within allowed directory (prevent path traversal)
        if (!$allowedRealPath || !str_starts_with($realPath, $allowedRealPath)) {
            SecurityLogger::logSuspiciousActivity('Path traversal attempt detected', [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'requested_path' => $userProvidedPath,
                'resolved_path' => $realPath,
                'allowed_directory' => $allowedRealPath,
                'user_agent' => request()->userAgent(),
            ]);
            
            // Log to security channel with high severity
            \Illuminate\Support\Facades\Log::channel('security')->critical('SECURITY: Path traversal attempt', [
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
                'path_requested' => $userProvidedPath,
                'path_resolved' => $realPath,
                'allowed_base' => $allowedRealPath,
            ]);
            
            abort(403, 'Access denied - Invalid file path');
        }
        
        // 8. Log successful file access
        SecurityLogger::logFileAccess('File downloaded', $realPath, [
            'filename' => $filename,
            'disk' => $disk,
        ]);
        
        // 9. Return file download response
        return response()->download($realPath);
    }

    /**
     * Securely get file URL with path validation
     * 
     * @param string $userProvidedPath User-supplied path or filename
     * @param string $allowedDirectory Allowed storage directory
     * @param string $disk Storage disk
     * @return string|null
     */
    public function secureUrl(string $userProvidedPath, string $allowedDirectory, string $disk = 'public'): ?string
    {
        // Extract basename only
        $filename = basename($userProvidedPath);
        
        // Construct relative path
        $relativePath = trim($allowedDirectory, '/') . '/' . $filename;
        
        // Check if file exists
        if (!Storage::disk($disk)->exists($relativePath)) {
            return null;
        }
        
        // Get full path for validation
        $fullPath = Storage::disk($disk)->path($relativePath);
        $realPath = realpath($fullPath);
        
        if (!$realPath) {
            return null;
        }
        
        // Validate path is within allowed directory
        $allowedRealPath = realpath(Storage::disk($disk)->path($allowedDirectory));
        
        if (!$allowedRealPath || !str_starts_with($realPath, $allowedRealPath)) {
            SecurityLogger::logSuspiciousActivity('Path traversal in URL generation', [
                'user_id' => auth()->id(),
                'requested_path' => $userProvidedPath,
            ]);
            return null;
        }
        
        // Return public URL
        return Storage::disk($disk)->url($relativePath);
    }

    /**
     * Validate file path is within allowed directory
     * 
     * @param string $filePath File path to validate
     * @param string $allowedDirectory Allowed base directory
     * @param string $disk Storage disk
     * @return bool
     */
    public function isPathSafe(string $filePath, string $allowedDirectory, string $disk = 'public'): bool
    {
        $filename = basename($filePath);
        $relativePath = trim($allowedDirectory, '/') . '/' . $filename;
        
        if (!Storage::disk($disk)->exists($relativePath)) {
            return false;
        }
        
        $fullPath = Storage::disk($disk)->path($relativePath);
        $realPath = realpath($fullPath);
        
        if (!$realPath) {
            return false;
        }
        
        $allowedRealPath = realpath(Storage::disk($disk)->path($allowedDirectory));
        
        return $allowedRealPath && str_starts_with($realPath, $allowedRealPath);
    }
}

