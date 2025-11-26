<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends BaseApiController
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    /**
     * Get all settings
     * 
     * GET /api/v1/admin/settings
     */
    public function index(Request $request)
    {
        $settings = Setting::orderBy('key')->get();

        // Group settings by category
        $grouped = $settings->groupBy(function ($setting) {
            // Extract category from key (e.g., "site.name" => "site")
            $parts = explode('.', $setting->key);
            return $parts[0] ?? 'general';
        });

        // Format response
        $formatted = [];
        foreach ($grouped as $category => $items) {
            $formatted[$category] = $items->mapWithKeys(function ($setting) {
                return [$setting->key => [
                    'value' => $setting->value,
                    'type' => $setting->type ?? 'string',
                    'description' => $setting->description ?? null,
                ]];
            });
        }

        return $this->success([
            'settings' => $formatted,
            'settings_list' => $settings->map(function ($setting) {
                return [
                    'key' => $setting->key,
                    'value' => $setting->value,
                    'type' => $setting->type ?? 'string',
                    'description' => $setting->description ?? null,
                ];
            }),
        ]);
    }

    /**
     * Update settings (batch update)
     * 
     * PATCH /api/v1/admin/settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['settings'] as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    ['value' => $setting['value'] ?? '']
                );
            }

            // Clear settings cache
            $this->settingsService->clearCache();

            DB::commit();

            return $this->success(null, 'Settings updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'SETTINGS_ERROR',
                400
            );
        }
    }

    /**
     * Update single setting
     * 
     * PATCH /api/v1/admin/settings/{key}
     */
    public function updateSingle(Request $request, string $key)
    {
        $validated = $request->validate([
            'value' => ['required'],
        ]);

        try {
            $setting = Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $validated['value']]
            );

            // Clear settings cache
            $this->settingsService->clearCache();

            return $this->success([
                'key' => $setting->key,
                'value' => $setting->value,
            ], 'Setting updated successfully');

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'SETTINGS_ERROR',
                400
            );
        }
    }

    /**
     * Get commission settings
     * 
     * GET /api/v1/admin/settings/commission
     */
    public function getCommission()
    {
        $productCommission = $this->settingsService->getCommissionForType('product');
        $serviceCommission = $this->settingsService->getCommissionForType('service');

        return $this->success([
            'product_commission' => $productCommission,
            'service_commission' => $serviceCommission,
        ]);
    }

    /**
     * Update commission settings
     * 
     * PATCH /api/v1/admin/settings/commission
     */
    public function updateCommission(Request $request)
    {
        $validated = $request->validate([
            'product_commission' => ['required', 'numeric', 'min:0', 'max:100'],
            'service_commission' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            DB::beginTransaction();

            Setting::updateOrCreate(
                ['key' => 'commission.product'],
                ['value' => $validated['product_commission']]
            );

            Setting::updateOrCreate(
                ['key' => 'commission.service'],
                ['value' => $validated['service_commission']]
            );

            $this->settingsService->clearCache();

            DB::commit();

            return $this->success([
                'product_commission' => $validated['product_commission'],
                'service_commission' => $validated['service_commission'],
            ], 'Commission settings updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'COMMISSION_ERROR',
                400
            );
        }
    }

    /**
     * Get Xendit settings
     * 
     * GET /api/v1/admin/settings/xendit
     */
    public function getXendit()
    {
        $xenditSettings = $this->settingsService->getXenditSettings();

        // Don't expose secret keys in full
        if (isset($xenditSettings['secret_key']) && !empty($xenditSettings['secret_key'])) {
            $xenditSettings['secret_key_masked'] = '***' . substr($xenditSettings['secret_key'], -4);
            unset($xenditSettings['secret_key']);
        }

        return $this->success($xenditSettings);
    }

    /**
     * Update Xendit settings
     * 
     * PATCH /api/v1/admin/settings/xendit
     */
    public function updateXendit(Request $request)
    {
        $validated = $request->validate([
            'secret_key' => ['nullable', 'string'],
            'webhook_token' => ['nullable', 'string'],
            'api_url' => ['nullable', 'url'],
            'production' => ['boolean'],
            'enable_xenplatform' => ['boolean'],
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    Setting::updateOrCreate(
                        ['key' => "xendit.{$key}"],
                        ['value' => $value]
                    );
                }
            }

            $this->settingsService->clearCache();

            DB::commit();

            return $this->success(null, 'Xendit settings updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'XENDIT_ERROR',
                400
            );
        }
    }

    /**
     * Get site settings
     * 
     * GET /api/v1/admin/settings/site
     */
    public function getSite()
    {
        return $this->success([
            'site_name' => $this->settingsService->get('site.name', 'Ebrystoree'),
            'site_description' => $this->settingsService->get('site.description', ''),
            'site_logo' => $this->settingsService->get('site.logo', ''),
            'site_url' => $this->settingsService->get('site.url', ''),
            'contact_email' => $this->settingsService->get('site.contact_email', ''),
            'contact_phone' => $this->settingsService->get('site.contact_phone', ''),
            'maintenance_mode' => $this->settingsService->get('site.maintenance_mode', false),
        ]);
    }

    /**
     * Update site settings
     * 
     * PATCH /api/v1/admin/settings/site
     */
    public function updateSite(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string'],
            'site_url' => ['nullable', 'url'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'maintenance_mode' => ['boolean'],
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated as $key => $value) {
                if ($value !== null) {
                    Setting::updateOrCreate(
                        ['key' => "site.{$key}"],
                        ['value' => $value]
                    );
                }
            }

            $this->settingsService->clearCache();

            DB::commit();

            return $this->success(null, 'Site settings updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'SITE_ERROR',
                400
            );
        }
    }

    /**
     * Clear all settings cache
     * 
     * POST /api/v1/admin/settings/clear-cache
     */
    public function clearCache()
    {
        try {
            $this->settingsService->clearCache();

            return $this->success(null, 'Settings cache cleared successfully');

        } catch (\Exception $e) {
            return $this->error(
                'Failed to clear cache',
                [],
                'CACHE_ERROR',
                500
            );
        }
    }
}

