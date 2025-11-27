<?php
    $settingsService = app(\App\Services\SettingsService::class);
    $isMaintenance = $settingsService->isMaintenanceMode();
?>

<?php if($isMaintenance && !(auth()->check() && auth()->user()->isAdmin())): ?>
<div class="bg-yellow-500/20 border-b-2 border-yellow-500/50 px-4 py-3 sm:py-4">
    <div class="container mx-auto flex items-center justify-between gap-4">
        <div class="flex items-center space-x-3 flex-1">
            <span class="text-2xl flex-shrink-0">🔧</span>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-yellow-400 text-sm sm:text-base">Maintenance Mode</h3>
                <p class="text-white/90 text-xs sm:text-sm">Platform sedang dalam mode maintenance. Beberapa fitur mungkin tidak tersedia. Silakan coba lagi nanti.</p>
            </div>
        </div>
        <button onclick="this.parentElement.parentElement.style.display='none'" 
                class="text-white/60 hover:text-white flex-shrink-0 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
<?php endif; ?>








<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/maintenance-banner.blade.php ENDPATH**/ ?>