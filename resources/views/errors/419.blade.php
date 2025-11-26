@extends('layouts.app')

@section('title', '419 - Sesi Kedaluwarsa')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-3xl mx-auto text-center">
        <!-- Decorative Background -->
        <div class="absolute inset-0 -z-10 opacity-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/30 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
        </div>
        
        <!-- 419 Icon -->
        <div class="mb-8 sm:mb-12 flex justify-center">
            <div class="relative">
                <!-- Large 419 Number -->
                <div class="text-[120px] sm:text-[180px] lg:text-[220px] font-bold text-purple-500/20 leading-none select-none">
                    419
                </div>
                <!-- Icon Overlay -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                <span class="text-purple-500">Sesi Kedaluwarsa</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Sesi Anda telah kedaluwarsa atau token CSRF tidak valid. Ini terjadi untuk keamanan Anda.
                Silakan muat ulang halaman dan coba lagi.
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 mb-8 sm:mb-12">
            <button onclick="window.location.reload()" 
                    class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl shadow-primary/50 text-base sm:text-lg font-semibold">
                <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Muat Ulang Halaman</span>
            </button>
            
            <button onclick="window.history.back()" 
                    class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 rounded-xl transition-all duration-300 hover:scale-105 text-base sm:text-lg font-semibold">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </button>
        </div>
        
        <!-- Info Box -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold mb-3 text-purple-400">💡 Mengapa ini terjadi?</h3>
            <p class="text-sm sm:text-base text-white/60 leading-relaxed">
                Untuk melindungi akun Anda dari serangan CSRF (Cross-Site Request Forgery), 
                kami membatasi masa aktif setiap sesi. Jika halaman dibuka terlalu lama tanpa aktivitas, 
                token keamanan akan kedaluwarsa dan Anda perlu muat ulang.
            </p>
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
@endsection


