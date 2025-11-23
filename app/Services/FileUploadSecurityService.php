<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadSecurityService
{
    /**
     * Enhanced file validation with content scanning
     */
    public function validateFile(UploadedFile $file, array $allowedMimeTypes, int $maxSizeKB = 2048): array
    {
        $errors = [];
        
        // 1. Check file size
        $fileSizeKB = $file->getSize() / 1024;
        if ($fileSizeKB > $maxSizeKB) {
            $errors[] = "File size ({$fileSizeKB}KB) melebihi batas maksimal ({$maxSizeKB}KB)";
        }
        
        // 2. Verify actual MIME type
        $actualMimeType = $file->getMimeType();
        if (!in_array($actualMimeType, $allowedMimeTypes)) {
            $errors[] = "File type tidak valid. MIME type terdeteksi: {$actualMimeType}";
        }
        
        // 3. Verify extension matches MIME type
        $extension = strtolower($file->getClientOriginalExtension());
        $extensionMimeMap = $this->getExtensionMimeMap();
        
        if (isset($extensionMimeMap[$extension])) {
            $expectedMimes = $extensionMimeMap[$extension];
            if (!in_array($actualMimeType, $expectedMimes)) {
                $errors[] = "File extension (.{$extension}) tidak sesuai dengan MIME type ({$actualMimeType})";
            }
        }
        
        // 4. Scan file content for malicious patterns
        $contentIssues = $this->scanFileContent($file);
        if (!empty($contentIssues)) {
            $errors = array_merge($errors, $contentIssues);
        }
        
        return $errors;
    }
    
    /**
     * Scan file content for malicious patterns
     */
    private function scanFileContent(UploadedFile $file): array
    {
        $issues = [];
        $fileContent = file_get_contents($file->getRealPath());
        $fileSize = strlen($fileContent);
        
        // Skip scanning for very large files (performance)
        if ($fileSize > 5 * 1024 * 1024) { // 5MB
            return $issues;
        }
        
        // Dangerous patterns to detect
        // Using simple string matching for literal patterns, regex for complex patterns
        $literalPatterns = [
            '<?php' => 'PHP code detected',
            '<script' => 'JavaScript code detected',
            'eval(' => 'Eval function detected',
            'base64_decode' => 'Base64 decode detected',
            'exec(' => 'Exec function detected',
            'system(' => 'System function detected',
            'shell_exec' => 'Shell exec detected',
            'passthru(' => 'Passthru function detected',
        ];
        
        // Check literal patterns (simple and safe)
        foreach ($literalPatterns as $pattern => $description) {
            if (stripos($fileContent, $pattern) !== false) {
                SecurityLogger::logSuspiciousActivity('Malicious file content detected', [
                    'file_name' => $file->getClientOriginalName(),
                    'pattern' => $pattern,
                    'description' => $description,
                    'file_size' => $fileSize,
                ]);
                $issues[] = "File mengandung konten yang mencurigakan: {$description}";
            }
        }
        
        // Check regex patterns (more complex)
        $regexPatterns = [
            '/preg_replace\s*\([^)]*\/e/i' => 'Preg replace with eval',
            '/file_get_contents\s*\([^)]*http/i' => 'Remote file inclusion attempt',
        ];
        
        foreach ($regexPatterns as $pattern => $description) {
            try {
                if (preg_match($pattern, $fileContent)) {
                    SecurityLogger::logSuspiciousActivity('Malicious file content detected', [
                        'file_name' => $file->getClientOriginalName(),
                        'pattern' => $pattern,
                        'description' => $description,
                        'file_size' => $fileSize,
                    ]);
                    $issues[] = "File mengandung konten yang mencurigakan: {$description}";
                }
            } catch (\Exception $e) {
                // Fail-secure: if regex compilation fails, reject the file
                Log::error('Regex pattern compilation failed in file scan - rejecting file for security', [
                    'pattern' => $pattern,
                    'error' => $e->getMessage(),
                    'file_name' => $file->getClientOriginalName(),
                ]);
                $issues[] = "Error dalam validasi keamanan file. File ditolak untuk keamanan.";
                break; // Stop scanning on error
            }
        }
        
        // Check for null bytes (path traversal attempt)
        // Skip null byte check for image files as they may legitimately contain null bytes
        $actualMimeType = $file->getMimeType();
        $isImageFile = strpos($actualMimeType, 'image/') === 0;
        
        if (!$isImageFile && strpos($fileContent, "\x00") !== false) {
            // For non-image files, null bytes are suspicious (could be path traversal)
            SecurityLogger::logSuspiciousActivity('Null byte detected in non-image file', [
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $actualMimeType,
            ]);
            $issues[] = "File mengandung null byte yang tidak diizinkan";
        } elseif ($isImageFile && strpos($fileContent, "\x00") !== false) {
            // For image files, only log if null byte is in first 100 bytes (suspicious)
            // Null bytes in image data are usually harmless
            $firstBytes = substr($fileContent, 0, 100);
            if (strpos($firstBytes, "\x00") !== false) {
                // Null byte in header/metadata area - could be suspicious
                SecurityLogger::logSuspiciousActivity('Null byte detected in image file header', [
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $actualMimeType,
                ]);
                // Don't reject, just log for monitoring
            }
        }
        
        return $issues;
    }
    
    /**
     * Generate secure unique filename
     */
    public function generateSecureFilename(UploadedFile $file, string $prefix = 'file'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $userId = auth()->id();
        $timestamp = time();
        $random = Str::random(16);
        
        // Format: prefix_userId_timestamp_random.extension
        return "{$prefix}_{$userId}_{$timestamp}_{$random}.{$extension}";
    }
    
    /**
     * Get extension to MIME type mapping
     */
    private function getExtensionMimeMap(): array
    {
        return [
            'jpg' => ['image/jpeg', 'image/jpg'],
            'jpeg' => ['image/jpeg', 'image/jpg'],
            'png' => ['image/png'],
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
            'ppt' => ['application/vnd.ms-powerpoint'],
            'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
            'zip' => ['application/zip'],
            'rar' => ['application/x-rar-compressed'],
            'txt' => ['text/plain'],
        ];
    }
}







