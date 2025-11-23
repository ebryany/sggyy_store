<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    public function about()
    {
        $platformSettings = $this->settingsService->getPlatformSettings();
        $contactInfo = $this->settingsService->getContactInfo();
        $ownerSettings = $this->settingsService->getOwnerSettings();
        
        return view('pages.about', [
            'siteName' => $platformSettings['site_name'] ?? 'Ebrystoree',
            'contactInfo' => $contactInfo,
            'ownerSettings' => $ownerSettings,
        ]);
    }
}

