<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\FileUploadSecurityService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingsService
{
    public function __construct(
        private FileUploadSecurityService $fileSecurityService
    ) {
    }

    /**
     * Get storage URL for a given path
     * Supports both local storage and cloud storage (OSS/S3)
     * 
     * @param string|null $path
     * @return string
     */
    private function getStorageUrl(?string $path): string
    {
        if (!$path) {
            return '';
        }
        
        // If already a full URL, return as is
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        
        // If already a relative URL starting with /
        if (str_starts_with($path, '/')) {
            return $path;
        }
        
        // Get default disk from config
        $disk = config('filesystems.default');
        
        // Use default disk (can be 'oss', 's3', or 'public')
        // For backward compatibility, check if file exists in 'public' first
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }
        
        // Try default disk
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        
        // If file doesn't exist, still return URL (for OSS/S3, URL might still work)
        return Storage::disk($disk)->url($path);
    }

    public function get(string $key, $default = null)
    {
        // Reduce cache TTL in development for real-time updates
        $ttl = app()->environment('production') ? 3600 : 60; // 1 hour in production, 1 minute in development
        return Cache::remember("setting.{$key}", $ttl, function () use ($key, $default) {
            return Setting::getValue($key, $default);
        });
    }

    public function set(string $key, $value, string $type = 'text'): void
    {
        Setting::setValue($key, $value, $type, auth()->id());
        // Clear both individual cache and all settings cache
        Cache::forget("setting.{$key}");
        Cache::forget('settings.all');
    }

    public function getAll(): array
    {
        // Reduce cache TTL in development for real-time updates
        $ttl = app()->environment('production') ? 3600 : 60; // 1 hour in production, 1 minute in development
        return Cache::remember('settings.all', $ttl, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('settings.all');
        Setting::pluck('key')->each(function ($key) {
            Cache::forget("setting.{$key}");
        });
    }

    public function getBusinessHours(): array
    {
        $hours = $this->get('business_hours', '{"open":"09:00","close":"21:00"}');
        return json_decode($hours, true) ?? ['open' => '09:00', 'close' => '21:00'];
    }

    public function getContactInfo(): array
    {
        return [
            'phone' => $this->get('contact_phone', ''),
            'email' => $this->get('contact_email', ''),
            'address' => $this->get('contact_address', ''),
            'whatsapp' => $this->get('contact_whatsapp', ''),
        ];
    }

    public function getBankAccountInfo(): array
    {
        return [
            'bank_name' => $this->get('bank_name', ''),
            'bank_account_number' => $this->get('bank_account_number', ''),
            'bank_account_name' => $this->get('bank_account_name', ''),
            'qris_code' => $this->get('qris_code', ''),
        ];
    }

    public function getPlatformSettings(): array
    {
        $logo = $this->get('logo', '');
        $favicon = $this->get('favicon', '');
        
        // Convert logo path to URL if it's a local file
        $logoUrl = $this->getStorageUrl($logo);
        
        // Convert favicon path to URL if it's a local file
        $faviconUrl = $this->getStorageUrl($favicon);
        
        return [
            'site_name' => $this->get('site_name', 'Ebrystoree'),
            'tagline' => $this->get('tagline', 'Marketplace terpercaya untuk produk digital dan jasa joki tugas'),
            'logo' => $logo, // Keep original path/URL for form
            'logo_url' => $logoUrl, // URL for display
            'favicon' => $favicon, // Keep original path/URL for form
            'favicon_url' => $faviconUrl, // URL for display
            'currency' => $this->get('currency', 'IDR'),
            'timezone' => $this->get('timezone', 'Asia/Jakarta'),
            'system_announcement' => $this->get('system_announcement', ''),
        ];
    }

    public function getCommissionSettings(): array
    {
        return [
            'commission_product' => (float) $this->get('commission_product', 10), // Default 10%
            'commission_service' => (float) $this->get('commission_service', 15), // Default 15%
        ];
    }

    public function getXenditSettings(): array
    {
        return [
            'secret_key' => $this->get('xendit_secret_key', ''),
            'public_key' => $this->get('xendit_public_key', ''),
            'webhook_token' => $this->get('xendit_webhook_token', ''),
            'api_url' => $this->get('xendit_api_url', 'https://api.xendit.co'),
            'production' => (bool) $this->get('xendit_production', false),
            'escrow_hold_period_days' => (int) $this->get('escrow_hold_period_days', 7),
            'enable_xenplatform' => (bool) $this->get('enable_xenplatform', false),
        ];
    }

    public function getLimits(): array
    {
        return [
            'min_topup_amount' => (float) $this->get('min_topup_amount', 10000),
            'max_topup_amount' => (float) $this->get('max_topup_amount', 10000000),
            'min_order_amount' => (float) $this->get('min_order_amount', 1000),
            'min_withdrawal_amount' => (float) $this->get('min_withdrawal_amount', 50000),
            'max_withdrawal_amount' => (float) $this->get('max_withdrawal_amount', 50000000),
        ];
    }

    public function getEmailSettings(): array
    {
        return [
            'admin_email' => $this->get('admin_email', ''),
            'email_from_name' => $this->get('email_from_name', 'Ebrystoree'),
            'email_from_address' => $this->get('email_from_address', 'noreply@ebrystoree.com'),
            // Escrow email notifications
            'enable_escrow_emails' => (bool) $this->get('enable_escrow_emails', true),
            'escrow_email_buyer' => (bool) $this->get('escrow_email_buyer', true),
            'escrow_email_seller' => (bool) $this->get('escrow_email_seller', true),
            'escrow_email_admin' => (bool) $this->get('escrow_email_admin', false),
        ];
    }

    public function getBannerSettings(): array
    {
        $bannerImage = $this->get('banner_image', '');
        $bannerImageUrl = $this->getStorageUrl($bannerImage);
        
        return [
            'banner_enabled' => (bool) $this->get('banner_enabled', true),
            'banner_title' => $this->get('banner_title', 'Selamat Datang di Ebrystoree'),
            'banner_subtitle' => $this->get('banner_subtitle', 'Marketplace terpercaya untuk produk digital dan jasa joki tugas'),
            'banner_image' => $bannerImage,
            'banner_image_url' => $bannerImageUrl,
            'banner_button_text' => $this->get('banner_button_text', 'Mulai Belanja'),
            'banner_button_link' => $this->get('banner_button_link', route('products.index')),
            'banner_overlay_opacity' => (float) $this->get('banner_overlay_opacity', 0.4),
        ];
    }

    public function getSeoSettings(): array
    {
        return [
            'meta_title' => $this->get('meta_title', 'Ebrystoree - Marketplace Produk Digital & Jasa Joki Tugas'),
            'meta_description' => $this->get('meta_description', ''),
            'meta_keywords' => $this->get('meta_keywords', ''),
        ];
    }

    public function getFeatureFlags(): array
    {
        $xenditSettings = $this->getXenditSettings();
        return [
            'enable_wallet' => (bool) $this->get('enable_wallet', true),
            'enable_bank_transfer' => (bool) $this->get('enable_bank_transfer', true),
            'enable_qris' => (bool) $this->get('enable_qris', true),
            'enable_xendit' => (bool) $this->get('enable_xendit', false),
            'enable_xenplatform' => $xenditSettings['enable_xenplatform'] ?? false,
            'enable_seller_registration' => (bool) $this->get('enable_seller_registration', true),
            'maintenance_mode' => (bool) $this->get('maintenance_mode', false),
        ];
    }

    public function isFeatureEnabled(string $flag): bool
    {
        $flags = $this->getFeatureFlags();
        return $flags[$flag] ?? false;
    }

    public function isMaintenanceMode(): bool
    {
        return $this->isFeatureEnabled('maintenance_mode');
    }

    public function getCommissionForType(string $type): float
    {
        $settings = $this->getCommissionSettings();
        return $type === 'product' 
            ? $settings['commission_product'] 
            : $settings['commission_service'];
    }

    public function getHomeSettings(): array
    {
        return [
            'hero_title' => $this->get('home_hero_title', 'Selamat Datang di'),
            'hero_subtitle' => $this->get('home_hero_subtitle', 'Ebrystoree'),
            'hero_description' => $this->get('home_hero_description', 'Marketplace terpercaya untuk produk digital dan jasa joki tugas berkualitas tinggi'),
            'hero_badge' => $this->get('home_hero_badge', '✨ Platform Terpercaya #1 di Indonesia'),
            'home_background_color' => $this->get('home_background_color', ''),
            'home_background_image' => $this->get('home_background_image', ''),
        ];
    }

    public function getOwnerSettings(): array
    {
        $badges = $this->get('owner_badges', '["Visionary Leader","Innovation Driven","Customer Focused"]');
        $photoPath = $this->get('owner_photo', '');
        
        // Convert storage path to URL if it's a local path
        $photoUrl = '';
        if ($photoPath) {
            // If it's already a full URL (starts with http:// or https://), use it as is
            if (str_starts_with($photoPath, 'http://') || str_starts_with($photoPath, 'https://')) {
                $photoUrl = $photoPath;
            } else {
                // It's a storage path, convert to URL
                // Remove leading slash if exists
                $photoPath = ltrim($photoPath, '/');
                
                // Generate URL using Storage facade
                $photoUrl = $this->getStorageUrl($photoPath);
                
                // Ensure URL is absolute (add domain if needed)
                if (!str_starts_with($photoUrl, 'http')) {
                    $baseUrl = rtrim(config('app.url'), '/');
                    $photoUrl = $baseUrl . '/' . ltrim($photoUrl, '/');
                }
            }
        }
        
        // Handle badges - check if it's already an array or needs decoding
        $badgesArray = ['Visionary Leader', 'Innovation Driven', 'Customer Focused'];
        if (is_string($badges)) {
            $decoded = json_decode($badges, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $badgesArray = $decoded;
            }
        } elseif (is_array($badges)) {
            $badgesArray = $badges;
        }
        
        return [
            'owner_name' => $this->get('owner_name', 'Febryanus Tambing'),
            'owner_title' => $this->get('owner_title', 'Owner & Founder'),
            'owner_description' => $this->get('owner_description', 'Dengan dedikasi dan visi yang kuat, saya membangun platform terpercaya yang menghubungkan kebutuhan digital dan akademik masyarakat Indonesia. Komitmen kami adalah memberikan layanan terbaik dengan kualitas premium dan pelayanan yang memuaskan.'),
            'owner_photo' => $photoUrl,
            'owner_badges' => $badgesArray,
        ];
    }

    /**
     * Upload logo file
     * 
     * @param UploadedFile $file
     * @return string Path to uploaded logo
     * @throws \Exception
     */
    public function uploadLogo(UploadedFile $file): string
    {
        try {
            // Security: Validate file
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml', 'image/webp'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 2048); // 2MB max
            
            if (!empty($validationErrors)) {
                throw new \Exception('File logo tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old logo if exists
            $oldLogo = $this->get('logo', '');
            if ($oldLogo && !str_starts_with($oldLogo, 'http')) {
                // Only delete if it's a local file (not external URL)
                $oldLogoPath = ltrim($oldLogo, '/');
                $disk = config('filesystems.default');
                if (Storage::disk($disk)->exists($oldLogoPath)) {
                    Storage::disk($disk)->delete($oldLogoPath);
                }
            }

            // Store new logo
            $disk = config('filesystems.default');
            $path = $file->store('settings/logo', $disk);

            Log::info('Logo uploaded', [
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to upload logo', [
                'error' => $e->getMessage(),
                'uploaded_by' => auth()->id(),
            ]);

            throw new \Exception('Gagal mengupload logo: ' . $e->getMessage());
        }
    }

    /**
     * Upload favicon file
     * 
     * @param UploadedFile $file
     * @return string Path to uploaded favicon
     * @throws \Exception
     */
    public function uploadFavicon(UploadedFile $file): string
    {
        try {
            // Security: Validate file
            $allowedMimeTypes = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml', 'image/jpeg', 'image/jpg'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 1024); // 1MB max for favicon
            
            if (!empty($validationErrors)) {
                throw new \Exception('File favicon tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old favicon if exists
            $oldFavicon = $this->get('favicon', '');
            if ($oldFavicon && !str_starts_with($oldFavicon, 'http')) {
                // Only delete if it's a local file (not external URL)
                $oldFaviconPath = ltrim($oldFavicon, '/');
                $disk = config('filesystems.default');
                if (Storage::disk($disk)->exists($oldFaviconPath)) {
                    Storage::disk($disk)->delete($oldFaviconPath);
                }
            }

            // Store new favicon
            $disk = config('filesystems.default');
            $path = $file->store('settings/favicon', $disk);

            Log::info('Favicon uploaded', [
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to upload favicon', [
                'error' => $e->getMessage(),
                'uploaded_by' => auth()->id(),
            ]);

            throw new \Exception('Gagal mengupload favicon: ' . $e->getMessage());
        }
    }

    /**
     * Upload banner image file
     * 
     * @param UploadedFile $file
     * @return string Path to uploaded banner
     * @throws \Exception
     */
    public function uploadBannerImage(UploadedFile $file): string
    {
        try {
            // Security: Validate file
            $allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 5120); // 5MB max for banner
            
            if (!empty($validationErrors)) {
                throw new \Exception('File banner tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old banner if exists
            $oldBanner = $this->get('banner_image', '');
            if ($oldBanner && !str_starts_with($oldBanner, 'http')) {
                // Only delete if it's a local file (not external URL)
                $oldBannerPath = ltrim($oldBanner, '/');
                $disk = config('filesystems.default');
                if (Storage::disk($disk)->exists($oldBannerPath)) {
                    Storage::disk($disk)->delete($oldBannerPath);
                }
            }

            // Store new banner
            $disk = config('filesystems.default');
            $path = $file->store('settings/banners', $disk);

            Log::info('Banner image uploaded', [
                'path' => $path,
                'uploaded_by' => auth()->id(),
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to upload banner image', [
                'error' => $e->getMessage(),
                'uploaded_by' => auth()->id(),
            ]);

            throw new \Exception('Gagal mengupload banner: ' . $e->getMessage());
        }
    }
}




