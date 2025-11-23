@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-24 min-h-[70vh] flex items-center justify-center">
    <div class="max-w-3xl mx-auto text-center">
        <!-- Decorative Background -->
        <div class="absolute inset-0 -z-10 opacity-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/30 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/20 rounded-full blur-[120px]"></div>
        </div>
        
        <!-- 404 Icon -->
        <div class="mb-8 sm:mb-12 flex justify-center">
            <div class="relative">
                <!-- Large 404 Number -->
                <div class="text-[120px] sm:text-[180px] lg:text-[220px] font-bold text-primary/20 leading-none select-none">
                    404
                </div>
                <!-- Icon Overlay -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
                        <x-icon name="file-x" class="w-16 h-16 sm:w-20 sm:h-20 text-primary" />
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Error Message -->
        <div class="mb-8 sm:mb-12">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 sm:mb-6">
                <span class="text-primary">Halaman Tidak Ditemukan</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed">
                Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan. 
                Silakan periksa kembali URL atau kembali ke halaman utama.
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6 mb-8 sm:mb-12">
            <a href="{{ route('home') }}" 
               class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-primary hover:bg-primary-dark rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl shadow-primary/50 text-base sm:text-lg font-semibold">
                <x-icon name="home" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                <span>Kembali ke Beranda</span>
            </a>
            
            <button onclick="window.history.back()" 
                    class="group inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 rounded-xl transition-all duration-300 hover:scale-105 text-base sm:text-lg font-semibold">
                <x-icon name="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform" />
                <span>Kembali</span>
            </button>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
            <h3 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-white/90">
                Mungkin Anda Mencari:
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('products.index') }}" 
                   class="group flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 hover:scale-105 border border-white/5 hover:border-primary/30">
                    <x-icon name="shopping-bag" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform" />
                    <span class="text-sm sm:text-base">Produk Digital</span>
                </a>
                
                <a href="{{ route('services.index') }}" 
                   class="group flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 hover:scale-105 border border-white/5 hover:border-primary/30">
                    <x-icon name="game" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform" />
                    <span class="text-sm sm:text-base">Jasa Joki Tugas</span>
                </a>
                
                @auth
                <a href="{{ route('dashboard') }}" 
                   class="group flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 hover:scale-105 border border-white/5 hover:border-primary/30">
                    <x-icon name="dashboard" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform" />
                    <span class="text-sm sm:text-base">Dashboard</span>
                </a>
                @else
                <a href="{{ route('login') }}" 
                   class="group flex items-center gap-3 p-4 bg-white/5 hover:bg-white/10 rounded-xl transition-all duration-300 hover:scale-105 border border-white/5 hover:border-primary/30">
                    <x-icon name="user" class="w-5 h-5 text-primary group-hover:scale-110 transition-transform" />
                    <span class="text-sm sm:text-base">Masuk</span>
                </a>
                @endauth
            </div>
        </div>
        
        <!-- Help Text -->
        <div class="mt-8 sm:mt-12 text-center">
            <p class="text-sm sm:text-base text-white/50">
                Jika Anda yakin ini adalah kesalahan, silakan 
                <a href="{{ route('about') }}" class="text-primary hover:text-primary-dark underline transition-colors">
                    hubungi kami
                </a>
            </p>
        </div>
    </div>
</div>

<style>
    /* Animation untuk 404 number */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .select-none {
        animation: float 6s ease-in-out infinite;
    }
</style>
@endsection







