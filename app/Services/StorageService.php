<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Storage Service
 * 
 * Centralized storage management with OSS/S3 support
 * Handles file uploads, deletions, and URL generation
 * 
 * 🔒 SECURITY: Validates storage configuration and handles errors gracefully
 */
class StorageService
{
    /**
     * Get the default storage disk
     * 
     * @return string
     */
    public function getDefaultDisk(): string
    {
        return config('filesystems.default', 'public');
    }

    /**
     * Check if OSS/S3 is configured and accessible
     * 
     * @param string|null $disk Disk name (default: from config)
     * @return bool
     */
    public function isCloudStorageConfigured(?string $disk = null): bool
    {
        $disk = $disk ?? $this->getDefaultDisk();
        
        // Local disks don't need cloud config
        if (in_array($disk, ['local', 'public'])) {
            return true;
        }

        // Check OSS configuration
        if ($disk === 'oss') {
            $required = [
                'OSS_ACCESS_KEY_ID',
                'OSS_ACCESS_KEY_SECRET',
                'OSS_BUCKET',
                'OSS_ENDPOINT',
            ];

            foreach ($required as $key) {
                if (empty(env($key))) {
                    Log::warning("OSS configuration missing: {$key}");
                    return false;
                }
            }

            // Try to connect to OSS
            try {
                Storage::disk('oss')->exists('test-connection.txt');
                return true;
            } catch (Exception $e) {
                Log::error('OSS connection test failed', [
                    'error' => $e->getMessage(),
                    'disk' => $disk,
                ]);
                return false;
            }
        }

        // Check S3 configuration
        if ($disk === 's3') {
            $required = [
                'AWS_ACCESS_KEY_ID',
                'AWS_SECRET_ACCESS_KEY',
                'AWS_BUCKET',
            ];

            foreach ($required as $key) {
                if (empty(env($key))) {
                    Log::warning("S3 configuration missing: {$key}");
                    return false;
                }
            }

            // Try to connect to S3
            try {
                Storage::disk('s3')->exists('test-connection.txt');
                return true;
            } catch (Exception $e) {
                Log::error('S3 connection test failed', [
                    'error' => $e->getMessage(),
                    'disk' => $disk,
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Store file with fallback to public disk if cloud storage fails
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Storage path (e.g., 'products/images')
     * @param string|null $disk Disk name (default: from config)
     * @return string File path
     */
    public function store(\Illuminate\Http\UploadedFile $file, string $path, ?string $disk = null): string
    {
        $disk = $disk ?? $this->getDefaultDisk();

        // If cloud storage is not configured, fallback to public
        if (!$this->isCloudStorageConfigured($disk)) {
            Log::warning("Cloud storage not configured, falling back to public disk", [
                'requested_disk' => $disk,
                'path' => $path,
            ]);
            $disk = 'public';
        }

        try {
            return $file->store($path, $disk);
        } catch (Exception $e) {
            Log::error('File storage failed', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            // Fallback to public disk
            if ($disk !== 'public') {
                Log::info('Falling back to public disk');
                return $file->store($path, 'public');
            }

            throw $e;
        }
    }

    /**
     * Store file as with custom filename
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Storage path
     * @param string $filename Custom filename
     * @param string|null $disk Disk name
     * @return string File path
     */
    public function storeAs(\Illuminate\Http\UploadedFile $file, string $path, string $filename, ?string $disk = null): string
    {
        $disk = $disk ?? $this->getDefaultDisk();

        // If cloud storage is not configured, fallback to public
        if (!$this->isCloudStorageConfigured($disk)) {
            Log::warning("Cloud storage not configured, falling back to public disk", [
                'requested_disk' => $disk,
                'path' => $path,
            ]);
            $disk = 'public';
        }

        try {
            return $file->storeAs($path, $filename, $disk);
        } catch (Exception $e) {
            Log::error('File storage failed', [
                'disk' => $disk,
                'path' => $path,
                'filename' => $filename,
                'error' => $e->getMessage(),
            ]);

            // Fallback to public disk
            if ($disk !== 'public') {
                Log::info('Falling back to public disk');
                return $file->storeAs($path, $filename, 'public');
            }

            throw $e;
        }
    }

    /**
     * Delete file from storage
     * 
     * @param string $path File path
     * @param string|null $disk Disk name
     * @return bool
     */
    public function delete(string $path, ?string $disk = null): bool
    {
        if (empty($path)) {
            return false;
        }

        $disk = $disk ?? $this->getDefaultDisk();

        try {
            // Try default disk first
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->delete($path);
            }

            // Fallback: try public disk
            if ($disk !== 'public' && Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }

            return false;
        } catch (Exception $e) {
            Log::error('File deletion failed', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get file URL
     * 
     * @param string $path File path
     * @param string|null $disk Disk name
     * @return string|null
     */
    public function url(string $path, ?string $disk = null): ?string
    {
        if (empty($path)) {
            return null;
        }

        // If already a full URL, return as is
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        $disk = $disk ?? $this->getDefaultDisk();

        try {
            // Try default disk
            if (Storage::disk($disk)->exists($path)) {
                return Storage::disk($disk)->url($path);
            }

            // Fallback: try public disk
            if ($disk !== 'public' && Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }

            // For OSS/S3, URL might still work even if exists() returns false
            // (due to eventual consistency or permissions)
            return Storage::disk($disk)->url($path);
        } catch (Exception $e) {
            Log::error('File URL generation failed', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            // Fallback to public disk
            if ($disk !== 'public') {
                try {
                    return Storage::disk('public')->url($path);
                } catch (Exception $e2) {
                    Log::error('Public disk URL generation also failed', [
                        'path' => $path,
                        'error' => $e2->getMessage(),
                    ]);
                }
            }

            return null;
        }
    }

    /**
     * Check if file exists
     * 
     * @param string $path File path
     * @param string|null $disk Disk name
     * @return bool
     */
    public function exists(string $path, ?string $disk = null): bool
    {
        if (empty($path)) {
            return false;
        }

        $disk = $disk ?? $this->getDefaultDisk();

        try {
            // Try default disk
            if (Storage::disk($disk)->exists($path)) {
                return true;
            }

            // Fallback: try public disk
            if ($disk !== 'public') {
                return Storage::disk('public')->exists($path);
            }

            return false;
        } catch (Exception $e) {
            Log::error('File existence check failed', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get signed URL for private files (OSS/S3)
     * 
     * @param string $path File path
     * @param int $expiration Expiration time in minutes (default: 60)
     * @param string|null $disk Disk name
     * @return string|null
     */
    public function temporaryUrl(string $path, int $expiration = 60, ?string $disk = null): ?string
    {
        if (empty($path)) {
            return null;
        }

        $disk = $disk ?? $this->getDefaultDisk();

        // Only cloud storage supports temporary URLs
        if (!in_array($disk, ['oss', 's3'])) {
            return $this->url($path, $disk);
        }

        try {
            return Storage::disk($disk)->temporaryUrl($path, now()->addMinutes($expiration));
        } catch (Exception $e) {
            Log::error('Temporary URL generation failed', [
                'disk' => $disk,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return $this->url($path, $disk);
        }
    }
}

