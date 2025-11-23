@extends('layouts.app')

@section('title', 'Login - Ebrystoree')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <div class="w-full max-w-md">
        <!-- Branding Header -->
        <div class="text-center mb-6 sm:mb-8">
            @php
                $settingsService = app(\App\Services\SettingsService::class);
                $platformSettings = $settingsService->getPlatformSettings();
                $siteName = $platformSettings['site_name'] ?? 'Ebrystoree';
                $logo = $platformSettings['logo'] ?? '';
            @endphp
            @if($logo)
            <div class="flex justify-center mb-4">
                <img src="{{ $logo }}" alt="{{ $siteName }}" class="h-12 sm:h-16 w-auto">
            </div>
            @endif
            <h1 class="text-3xl sm:text-4xl font-bold mb-2 text-primary">
                Selamat Datang Kembali
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        <!-- Login Form -->
        <div class="glass p-6 sm:p-8 rounded-xl shadow-2xl border border-white/10">
            <form method="POST" action="{{ route('login') }}" x-data="{ showPassword: false }">
                @csrf
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Email</label>
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required
                               autocomplete="email"
                               placeholder="nama@email.com"
                               class="w-full glass border {{ $errors->has('email') ? 'border-red-500 pr-12' : 'border-white/10 pr-4' }} rounded-lg px-4 py-3 bg-white/5 focus:outline-none {{ $errors->has('email') ? 'focus:border-red-500 focus:ring-2 focus:ring-red-500/20' : 'focus:border-primary focus:ring-2 focus:ring-primary/20' }} transition-all text-base touch-target placeholder:text-white/60">
                        @error('email')
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('email')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                        <x-icon name="alert" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required
                               autocomplete="current-password"
                               placeholder="Masukkan password Anda"
                               class="w-full glass border {{ $errors->has('password') ? 'border-red-500 pr-12' : 'border-white/10 pr-12' }} rounded-lg px-4 py-3 bg-white/5 focus:outline-none {{ $errors->has('password') ? 'focus:border-red-500 focus:ring-2 focus:ring-red-500/20' : 'focus:border-primary focus:ring-2 focus:ring-primary/20' }} transition-all text-base touch-target placeholder:text-white/60">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 z-10 flex items-center gap-2">
                            @error('password')
                            <div class="pointer-events-none">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                            </div>
                            @enderror
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="text-white/70 hover:text-white transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                        <x-icon name="alert" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary focus:ring-2 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm text-white/70 group-hover:text-white transition-colors">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors">
                        Lupa password?
                    </a>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-primary hover:bg-primary-dark rounded-lg px-4 py-3.5 transition-colors touch-target text-base font-semibold flex items-center justify-center gap-2 text-white">
                    <span>Masuk</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-dark text-white/40">atau</span>
                </div>
            </div>
            
            <!-- Register Link -->
            <p class="text-center text-sm sm:text-base text-white/60">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-primary hover:text-primary-dark font-semibold transition-colors touch-target">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <!-- Additional Info -->
        <p class="text-center mt-6 text-xs text-white/40">
            Dengan masuk, Anda menyetujui 
            <a href="#" class="text-primary hover:underline">Syarat & Ketentuan</a> 
            dan 
            <a href="#" class="text-primary hover:underline">Kebijakan Privasi</a>
        </p>
    </div>
</div>
@endsection
