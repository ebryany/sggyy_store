@extends('layouts.app')

@section('title', '429 - Terlalu Banyak Permintaan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-3xl mx-auto text-center">
        <!-- Decorative Background -->
        <div class="absolute inset-0 -z-10 opacity-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-amber-500/30 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
        </div>
        
        <!-- 429 Icon -->
        <div class="mb-8 sm:mb-12 flex justify-center">
            <div class="relative">
                <!-- Large 429 Number -->
                <div class="text-[120px] sm:text-[180px] lg:text-[220px] font-bold text-amber-500/20 leading-none select-none">
                    429
                </div>
                <!-- Icon Overlay -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                <span class="text-amber-500">Terlalu Banyak Permintaan</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Anda telah mengirimkan terlalu banyak permintaan dalam waktu singkat. 
                Untuk keamanan, akses Anda dibatasi sementara.
            </p>
        </div>
        
        <!-- Countdown Timer (if retry-after header available) -->
        @php
            $retryAfter = session('retry_after', 60); // Default 60 seconds
        @endphp
        
        <div class="mb-8 sm:mb-12">
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 inline-block">
                <p class="text-sm text-white/60 mb-2">Silakan coba lagi dalam:</p>
                <div class="text-4xl sm:text-5xl font-bold text-amber-500" id="countdown">
                    {{ $retryAfter }}s
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 mb-8 sm:mb-12">
            <a href="{{ route('home') }}" 
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl shadow-primary/50 text-base sm:text-lg font-semibold">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
        
        <!-- Info Box -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold mb-3 text-amber-400">🛡️ Rate Limiting</h3>
            <p class="text-sm sm:text-base text-white/60 leading-relaxed mb-4">
                Kami membatasi jumlah permintaan untuk melindungi server dan mencegah penyalahgunaan. 
                Jika Anda sering melihat halaman ini, mungkin ada masalah dengan koneksi Anda.
            </p>
            <p class="text-sm text-white/50">
                Butuh bantuan? 
                <a href="{{ route('about') }}" class="text-primary hover:text-primary-dark underline transition-colors">
                    Hubungi kami
                </a>
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

<script>
    // Countdown timer
    let seconds = {{ $retryAfter }};
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'Memuat ulang...';
            setTimeout(() => window.location.reload(), 500);
        } else {
            countdownEl.textContent = seconds + 's';
        }
    }, 1000);
</script>
@endsection

