@extends('layouts.app')

@section('title', '503 - Dalam Maintenance')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-3xl mx-auto text-center">
        <!-- Decorative Background -->
        <div class="absolute inset-0 -z-10 opacity-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/30 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
        </div>
        
        <!-- 503 Icon -->
        <div class="mb-8 sm:mb-12 flex justify-center">
            <div class="relative">
                <!-- Large 503 Number -->
                <div class="text-[120px] sm:text-[180px] lg:text-[220px] font-bold text-blue-500/20 leading-none select-none">
                    503
                </div>
                <!-- Icon Overlay -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                <span class="text-blue-500">Dalam Mode Maintenance</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Kami sedang melakukan pemeliharaan sistem untuk meningkatkan performa dan keamanan. 
                Mohon maaf atas ketidaknyamanannya.
            </p>
        </div>
        
        <!-- Estimated Time -->
        <div class="mb-8 sm:mb-12">
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 inline-block">
                <p class="text-sm text-white/60 mb-2">Estimasi waktu:</p>
                <div class="text-2xl sm:text-3xl font-bold text-blue-500">
                    🕐 15 - 30 Menit
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 mb-8 sm:mb-12">
            <button onclick="window.location.reload()" 
                    class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl shadow-primary/50 text-base sm:text-lg font-semibold">
                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Muat Ulang</span>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="mb-8 sm:mb-12">
            <div class="w-full bg-white/10 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-primary to-blue-500 h-full rounded-full animate-pulse" style="width: 65%"></div>
            </div>
            <p class="text-sm text-white/50 mt-3">Maintenance in progress...</p>
        </div>
        
        <!-- Info Box -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold mb-3 text-blue-400">📢 Update Terbaru</h3>
            <ul class="text-sm sm:text-base text-white/60 text-left space-y-2">
                <li class="flex items-start gap-3">
                    <span class="text-green-500 mt-0.5">✓</span>
                    <span>Security enhancement & bug fixes</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-green-500 mt-0.5">✓</span>
                    <span>Performance optimization</span>
                </li>
                <li class="flex items-start gap-3">
                    <span class="text-blue-500 mt-0.5">⟳</span>
                    <span>Database migration (in progress)</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .select-none { animation: float 6s ease-in-out infinite; }
</style>

<script>
    // Auto-reload setiap 30 detik
    setTimeout(() => {
        window.location.reload();
    }, 30000);
</script>
@endsection

