@php
    $settingsService = app(\App\Services\SettingsService::class);
    $platformSettings = $settingsService->getPlatformSettings();
    $announcement = $platformSettings['system_announcement'] ?? '';
@endphp

@if(!empty($announcement))
<div class="relative w-full overflow-hidden bg-gradient-to-r from-primary/20 via-primary/10 to-primary/20 border-b border-primary/30 py-2.5 sm:py-3 z-40" id="system-announcement-banner">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 h-full">
            <!-- Icon - Sejajar dengan logo Ebrystoree -->
            <div class="flex-shrink-0 z-10">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-primary/30 backdrop-blur-sm flex items-center justify-center border border-primary/50">
                    <x-icon name="bell" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-primary animate-pulse" />
                </div>
            </div>
            
            <!-- Marquee Container -->
            <div class="flex-1 overflow-hidden relative" style="height: 24px;">
                <div class="marquee-wrapper">
                    <div class="marquee-content">
                        <span class="announcement-text">{{ $announcement }}</span>
                        <span class="announcement-text">{{ $announcement }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #system-announcement-banner {
        position: relative;
    }
    
    .marquee-wrapper {
        width: 100%;
        overflow: hidden;
        position: relative;
        height: 100%;
    }
    
    .marquee-content {
        display: inline-flex;
        white-space: nowrap;
        animation: marquee-scroll 10s linear infinite;
        will-change: transform;
    }
    
    .announcement-text {
        display: inline-block;
        padding-right: 100px;
        font-size: 0.875rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        white-space: nowrap;
    }
    
    @media (min-width: 640px) {
        .announcement-text {
            font-size: 1rem;
        }
    }
    
    @keyframes marquee-scroll {
        0% {
            transform: translateX(100%);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    /* Pause on hover */
    #system-announcement-banner:hover .marquee-content {
        animation-play-state: paused;
    }
    
    /* Smooth animation */
    @media (prefers-reduced-motion: no-preference) {
        .marquee-content {
            animation: marquee-scroll 10s linear infinite;
        }
    }
    
    /* For users who prefer reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .marquee-content {
            animation: none;
            justify-content: center;
        }
        
        .announcement-text {
            padding-right: 0;
        }
        
        .announcement-text:not(:first-child) {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const banner = document.getElementById('system-announcement-banner');
        if (!banner) return;
        
        const marqueeContent = banner.querySelector('.marquee-content');
        const announcementText = banner.querySelector('.announcement-text');
        
        if (marqueeContent && announcementText) {
            // Calculate optimal animation duration based on text length
            // Faster speed for quicker animation
            const textWidth = announcementText.scrollWidth + 100; // Include padding
            const speed = 80; // pixels per second (increased from 50 for faster animation)
            const duration = Math.max(8, textWidth / speed); // Minimum 8 seconds for faster animation
            
            // Apply dynamic duration
            marqueeContent.style.animationDuration = duration + 's';
        }
    });
</script>
@endif

