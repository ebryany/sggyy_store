<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index(): View
    {
        // Clear cache before getting settings to ensure fresh data
        $this->settingsService->clearCache();
        
        $settings = $this->settingsService->getAll();
        $businessHours = $this->settingsService->getBusinessHours();
        $contactInfo = $this->settingsService->getContactInfo();
        $bankAccountInfo = $this->settingsService->getBankAccountInfo();
        $platformSettings = $this->settingsService->getPlatformSettings();
        $commissionSettings = $this->settingsService->getCommissionSettings();
        $limits = $this->settingsService->getLimits();
        $emailSettings = $this->settingsService->getEmailSettings();
        $seoSettings = $this->settingsService->getSeoSettings();
        $featureFlags = $this->settingsService->getFeatureFlags();
        $homeSettings = $this->settingsService->getHomeSettings();
        $ownerSettings = $this->settingsService->getOwnerSettings();
        $bannerSettings = $this->settingsService->getBannerSettings();

        return view('settings.index', compact(
            'settings',
            'businessHours',
            'contactInfo',
            'bankAccountInfo',
            'platformSettings',
            'commissionSettings',
            'limits',
            'emailSettings',
            'seoSettings',
            'featureFlags',
            'homeSettings',
            'ownerSettings',
            'bannerSettings'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'key' => ['required', 'string'],
            'value' => ['required'],
            'type' => ['sometimes', 'string', 'in:text,number,json,boolean'],
        ]);

        try {
            $this->settingsService->set(
                $request->key,
                $request->value,
                $request->type ?? 'text'
            );

            return back()->with('success', 'Setting berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateBusinessHours(Request $request): RedirectResponse
    {
        $request->validate([
            'open' => ['required', 'string', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'close' => ['required', 'string', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
        ]);

        try {
            $hours = json_encode([
                'open' => $request->open,
                'close' => $request->close,
            ]);

            $this->settingsService->set('business_hours', $hours, 'json');

            return back()->with('success', 'Jam operasional berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateContactInfo(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'whatsapp' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        try {
            if ($request->has('phone')) {
                $this->settingsService->set('contact_phone', $request->phone);
            }
            if ($request->has('email')) {
                $this->settingsService->set('contact_email', $request->email);
            }
            if ($request->has('address')) {
                $this->settingsService->set('contact_address', $request->address);
            }
            if ($request->has('whatsapp')) {
                $this->settingsService->set('contact_whatsapp', $request->whatsapp);
            }

            return back()->with('success', 'Informasi kontak berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateBankAccountInfo(Request $request): RedirectResponse
    {
        $request->validate([
            'bank_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_account_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'bank_account_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'qris_code' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        try {
            if ($request->has('bank_name')) {
                $this->settingsService->set('bank_name', $request->bank_name);
            }
            if ($request->has('bank_account_number')) {
                $this->settingsService->set('bank_account_number', $request->bank_account_number);
            }
            if ($request->has('bank_account_name')) {
                $this->settingsService->set('bank_account_name', $request->bank_account_name);
            }
            if ($request->has('qris_code')) {
                $this->settingsService->set('qris_code', $request->qris_code);
            }

            return back()->with('success', 'Informasi rekening bank berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updatePlatformSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'site_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'tagline' => ['sometimes', 'nullable', 'string', 'max:500'],
            'logo' => ['sometimes', 'nullable', 'string', 'max:500'], // URL (if using URL)
            'logo_file' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'favicon' => ['sometimes', 'nullable', 'string', 'max:500'], // URL (if using URL)
            'favicon_file' => ['sometimes', 'nullable', 'image', 'mimes:ico,png,svg,jpeg,jpg', 'max:1024'],
            'currency' => ['sometimes', 'nullable', 'string', 'max:10'],
            'timezone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'system_announcement' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        try {
            if ($request->has('site_name')) {
                $this->settingsService->set('site_name', $request->site_name);
            }
            if ($request->has('tagline')) {
                $this->settingsService->set('tagline', $request->tagline);
            }
            
            // Handle Logo: File upload takes priority over URL
            if ($request->hasFile('logo_file')) {
                $logoPath = $this->settingsService->uploadLogo($request->file('logo_file'));
                $this->settingsService->set('logo', $logoPath);
                // Clear cache to ensure logo is immediately visible
                $this->settingsService->clearCache();
            } elseif ($request->has('logo') && $request->logo) {
                // Use URL if provided and no file uploaded
                $this->settingsService->set('logo', $request->logo);
                // Clear cache to ensure logo is immediately visible
                $this->settingsService->clearCache();
            }
            
            // Handle Favicon: File upload takes priority over URL
            if ($request->hasFile('favicon_file')) {
                $faviconPath = $this->settingsService->uploadFavicon($request->file('favicon_file'));
                $this->settingsService->set('favicon', $faviconPath);
            } elseif ($request->has('favicon') && $request->favicon) {
                // Use URL if provided and no file uploaded
                $this->settingsService->set('favicon', $request->favicon);
            }
            
            if ($request->has('currency')) {
                $this->settingsService->set('currency', $request->currency);
            }
            if ($request->has('timezone')) {
                $this->settingsService->set('timezone', $request->timezone);
            }
            if ($request->has('system_announcement')) {
                $this->settingsService->set('system_announcement', $request->system_announcement);
            }

            return back()->with('success', 'Pengaturan platform berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function updateCommissionSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'commission_product' => ['required', 'numeric', 'min:0', 'max:100'],
            'commission_service' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            $this->settingsService->set('commission_product', $request->commission_product, 'number');
            $this->settingsService->set('commission_service', $request->commission_service, 'number');

            return back()->with('success', 'Pengaturan komisi berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateLimits(Request $request): RedirectResponse
    {
        $request->validate([
            'min_topup_amount' => ['required', 'numeric', 'min:1000'],
            'max_topup_amount' => ['required', 'numeric', 'min:1000'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
            'min_withdrawal_amount' => ['required', 'numeric', 'min:0'],
            'max_withdrawal_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $this->settingsService->set('min_topup_amount', $request->min_topup_amount, 'number');
            $this->settingsService->set('max_topup_amount', $request->max_topup_amount, 'number');
            $this->settingsService->set('min_order_amount', $request->min_order_amount, 'number');
            $this->settingsService->set('min_withdrawal_amount', $request->min_withdrawal_amount, 'number');
            $this->settingsService->set('max_withdrawal_amount', $request->max_withdrawal_amount, 'number');

            return back()->with('success', 'Pengaturan limit berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateEmailSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'admin_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'email_from_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email_from_address' => ['sometimes', 'nullable', 'email', 'max:255'],
        ]);

        try {
            if ($request->has('admin_email')) {
                $this->settingsService->set('admin_email', $request->admin_email);
            }
            if ($request->has('email_from_name')) {
                $this->settingsService->set('email_from_name', $request->email_from_name);
            }
            if ($request->has('email_from_address')) {
                $this->settingsService->set('email_from_address', $request->email_from_address);
            }

            return back()->with('success', 'Pengaturan email berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateSeoSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'meta_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'meta_description' => ['sometimes', 'nullable', 'string', 'max:500'],
            'meta_keywords' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        try {
            if ($request->has('meta_title')) {
                $this->settingsService->set('meta_title', $request->meta_title);
            }
            if ($request->has('meta_description')) {
                $this->settingsService->set('meta_description', $request->meta_description);
            }
            if ($request->has('meta_keywords')) {
                $this->settingsService->set('meta_keywords', $request->meta_keywords);
            }

            return back()->with('success', 'Pengaturan SEO berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateFeatureFlags(Request $request): RedirectResponse
    {
        $request->validate([
            'enable_wallet' => ['sometimes', 'boolean'],
            'enable_bank_transfer' => ['sometimes', 'boolean'],
            'enable_qris' => ['sometimes', 'boolean'],
            'enable_seller_registration' => ['sometimes', 'boolean'],
            'maintenance_mode' => ['sometimes', 'boolean'],
        ]);

        try {
            $this->settingsService->set('enable_wallet', $request->boolean('enable_wallet', true), 'boolean');
            $this->settingsService->set('enable_bank_transfer', $request->boolean('enable_bank_transfer', true), 'boolean');
            $this->settingsService->set('enable_qris', $request->boolean('enable_qris', true), 'boolean');
            $this->settingsService->set('enable_seller_registration', $request->boolean('enable_seller_registration', true), 'boolean');
            $this->settingsService->set('maintenance_mode', $request->boolean('maintenance_mode', false), 'boolean');

            return back()->with('success', 'Pengaturan fitur berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateHomeSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'hero_subtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'hero_description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'hero_badge' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home_background_color' => ['sometimes', 'nullable', 'string', 'max:50'],
            'home_background_image' => ['sometimes', 'nullable', 'string', 'max:500'],
        ]);

        try {
            if ($request->has('hero_title')) {
                $this->settingsService->set('home_hero_title', $request->hero_title);
            }
            if ($request->has('hero_subtitle')) {
                $this->settingsService->set('home_hero_subtitle', $request->hero_subtitle);
            }
            if ($request->has('hero_description')) {
                $this->settingsService->set('home_hero_description', $request->hero_description);
            }
            if ($request->has('hero_badge')) {
                $this->settingsService->set('home_hero_badge', $request->hero_badge);
            }
            if ($request->has('home_background_color')) {
                $this->settingsService->set('home_background_color', $request->home_background_color);
            }
            if ($request->has('home_background_image')) {
                $this->settingsService->set('home_background_image', $request->home_background_image);
            }

            return back()->with('success', 'Pengaturan halaman home berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateOwnerSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'owner_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'owner_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'owner_description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'owner_photo_file' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:5120'], // 5MB max
            'owner_badges' => ['sometimes', 'nullable', 'string'],
        ]);

        try {
            if ($request->has('owner_name')) {
                $this->settingsService->set('owner_name', $request->owner_name);
            }
            if ($request->has('owner_title')) {
                $this->settingsService->set('owner_title', $request->owner_title);
            }
            if ($request->has('owner_description')) {
                $this->settingsService->set('owner_description', $request->owner_description);
            }
            
            // Handle file upload
            if ($request->hasFile('owner_photo_file')) {
                $file = $request->file('owner_photo_file');
                
                // Delete old photo if exists
                $disk = config('filesystems.default');
                $oldPhoto = $this->settingsService->get('owner_photo', '');
                if ($oldPhoto && Storage::disk($disk)->exists($oldPhoto)) {
                    Storage::disk($disk)->delete($oldPhoto);
                }
                
                // Store new photo
                $path = $file->store('owner/photos', $disk);
                $this->settingsService->set('owner_photo', $path);
                
                // Clear cache to ensure new photo is visible
                $this->settingsService->clearCache();
            }
            
            if ($request->has('owner_badges')) {
                $this->settingsService->set('owner_badges', $request->owner_badges, 'json');
            }

            return back()->with('success', 'Pengaturan Owner berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function storeFeaturedItem(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:product,service'],
            'item_id' => ['required', 'integer'],
            'title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:1000'],
            'header_bg_color' => ['sometimes', 'nullable', 'string', 'max:7'],
            'banner_bg_color' => ['sometimes', 'nullable', 'string', 'max:7'],
            'main_bg_color' => ['sometimes', 'nullable', 'string', 'max:7'],
            'main_text_color' => ['sometimes', 'nullable', 'string', 'max:7'],
            'accent_color' => ['sometimes', 'nullable', 'string', 'max:7'],
            'features' => ['sometimes', 'nullable', 'string'],
            'footer_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'sort_order' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'nullable', 'boolean'],
        ]);

        try {
            // Verify item exists
            if ($request->type === 'product') {
                $item = \App\Models\Product::findOrFail($request->item_id);
            } else {
                $item = \App\Models\Service::findOrFail($request->item_id);
            }

            // Parse features if provided
            $features = null;
            if ($request->has('features') && !empty($request->features)) {
                $decoded = json_decode($request->features, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $features = $decoded;
                }
            }

            \App\Models\FeaturedItem::create([
                'type' => $request->type,
                'item_id' => $request->item_id,
                'title' => $request->title,
                'description' => $request->description,
                'header_bg_color' => $request->header_bg_color ?? '#8B4513',
                'banner_bg_color' => $request->banner_bg_color,
                'main_bg_color' => $request->main_bg_color,
                'main_text_color' => $request->main_text_color ?? '#FFFFFF',
                'accent_color' => $request->accent_color,
                'features' => $features,
                'footer_text' => $request->footer_text,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
            ]);

            return back()->with('success', 'Featured item berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function deleteFeaturedItem(\App\Models\FeaturedItem $featuredItem): RedirectResponse
    {
        try {
            $featuredItem->delete();
            return back()->with('success', 'Featured item berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update API settings (Khfy Store API key)
     */
    public function updateApiSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'khfy_api_key' => ['required', 'string', 'min:10'],
        ], [
            'khfy_api_key.required' => 'API key harus diisi',
            'khfy_api_key.min' => 'API key terlalu pendek',
        ]);

        try {
            // Save API key
            $this->settingsService->set('khfy_api_key', $request->khfy_api_key, 'text');
            
            // Clear cache to ensure fresh data
            $this->settingsService->clearCache();
            
            // Verify it was saved (bypass cache)
            $savedKey = $this->settingsService->get('khfy_api_key');
            
            if ($savedKey !== $request->khfy_api_key) {
                \Log::error('API key save verification failed', [
                    'expected' => substr($request->khfy_api_key, 0, 8) . '...',
                    'saved' => $savedKey ? substr($savedKey, 0, 8) . '...' : 'null',
                ]);
                return back()->withErrors(['error' => 'Gagal menyimpan API key. Silakan coba lagi.']);
            }
            
            \Log::info('API key saved successfully', [
                'key_length' => strlen($savedKey),
                'key_prefix' => substr($savedKey, 0, 8) . '...',
                'saved_by' => auth()->id(),
            ]);
            
            // Pass updated API key to view via session flash to ensure it displays immediately
            return redirect()->route('admin.settings.index', ['tab' => 'api'])
                ->with('success', 'API settings berhasil diperbarui')
                ->with('updated_api_key', $request->khfy_api_key);
        } catch (\Exception $e) {
            \Log::error('Failed to save API key', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Sync products from Khfy Store API
     */
    public function syncProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $quotaService = app(\App\Services\QuotaService::class);
            
            // Clear cache before fetching to get fresh data
            $quotaService->clearProductsCache();
            
            // Fetch fresh products from API
            $products = $quotaService->getProducts();
            
            if (empty($products) || !is_array($products)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada produk yang ditemukan dari API atau format response tidak valid.',
                ], 400);
            }
            
            $count = 0;
            $errors = [];
            
            // Flatten grouped products for syncing
            $allProducts = [];
            foreach ($products as $provider => $providerProducts) {
                foreach ($providerProducts as $product) {
                    $allProducts[] = $product;
                }
            }
            
            foreach ($allProducts as $product) {
                try {
                    // Handle both normalized and original format
                    $productCode = $product['kode'] ?? $product['kode_produk'] ?? $product['code'] ?? null;
                    $productName = $product['nama'] ?? $product['nama_produk'] ?? $product['name'] ?? 'Produk Khfy Store';
                    $productPrice = $product['harga'] ?? $product['harga_final'] ?? $product['price'] ?? 0;
                    $productDesc = $product['deskripsi'] ?? $product['description'] ?? $product['desk'] ?? 'Produk kuota dari Khfy Store';
                    
                    // Cek apakah produk sudah ada berdasarkan kode atau nama
                    $existingProduct = \App\Models\Product::where('title', $productName)
                        ->orWhere('slug', \Illuminate\Support\Str::slug($productName))
                        ->orWhere('sku', $productCode)
                        ->first();
                    
                    if ($existingProduct) {
                        continue; // Skip jika sudah ada
                    }
                    
                    // Buat produk baru
                    \App\Models\Product::create([
                        'user_id' => auth()->id(), // Admin user
                        'title' => $productName,
                        'slug' => \App\Models\Product::generateSlug($productName),
                        'description' => $productDesc,
                        'short_description' => $productDesc,
                        'price' => $productPrice,
                        'stock' => 999, // Unlimited stock untuk produk quota
                        'category' => 'Quota',
                        'product_type' => 'digital',
                        'sku' => $productCode,
                        'is_active' => true,
                        'is_draft' => false,
                        'published_at' => now(),
                        'sold_count' => 0,
                        'views_count' => 0,
                    ]);
                    
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                    \Log::error('Error syncing product: ' . $e->getMessage(), [
                        'product' => $product,
                    ]);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $count > 0 
                    ? "Berhasil menambahkan {$count} produk baru dari Khfy Store. Provider dan produk sekarang tersedia di halaman Quota." 
                    : 'Tidak ada produk baru yang ditambahkan (semua produk sudah ada).',
                'count' => $count,
                'errors' => $errors,
                'redirect' => route('quota.index'), // Suggest redirect to quota page
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error syncing products from API: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil produk dari API: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateBannerSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'banner_enabled' => ['sometimes', 'boolean'],
            'banner_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'banner_subtitle' => ['sometimes', 'nullable', 'string', 'max:500'],
            'banner_image_file' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'], // 5MB max
            'banner_image' => ['sometimes', 'nullable', 'string', 'max:500'], // URL (if using URL)
            'banner_button_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'banner_button_link' => ['sometimes', 'nullable', 'string', 'max:500'],
            'banner_overlay_opacity' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:1'],
        ]);

        try {
            if ($request->has('banner_enabled')) {
                $this->settingsService->set('banner_enabled', $request->boolean('banner_enabled'), 'boolean');
            }
            if ($request->has('banner_title')) {
                $this->settingsService->set('banner_title', $request->banner_title);
            }
            if ($request->has('banner_subtitle')) {
                $this->settingsService->set('banner_subtitle', $request->banner_subtitle);
            }
            
            // Handle Banner Image: File upload takes priority over URL
            if ($request->hasFile('banner_image_file')) {
                $bannerPath = $this->settingsService->uploadBannerImage($request->file('banner_image_file'));
                $this->settingsService->set('banner_image', $bannerPath);
            } elseif ($request->has('banner_image') && $request->banner_image) {
                // Use URL if provided and no file uploaded
                $this->settingsService->set('banner_image', $request->banner_image);
            }
            
            if ($request->has('banner_button_text')) {
                $this->settingsService->set('banner_button_text', $request->banner_button_text);
            }
            if ($request->has('banner_button_link')) {
                $this->settingsService->set('banner_button_link', $request->banner_button_link);
            }
            if ($request->has('banner_overlay_opacity')) {
                $this->settingsService->set('banner_overlay_opacity', $request->banner_overlay_opacity, 'number');
            }

            return back()->with('success', 'Pengaturan banner berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
