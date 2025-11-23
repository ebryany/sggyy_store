@extends('layouts.app')

@section('title', 'Profile Saya - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Profile Completion Section -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 sm:mb-6 border border-white/10">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-base sm:text-lg font-semibold text-white/90">Completion</h2>
            <span class="text-xl sm:text-2xl font-bold text-primary">{{ $completion['percentage'] }}%</span>
        </div>
        <div class="w-full h-2 sm:h-3 bg-white/10 rounded-full overflow-hidden mb-2 sm:mb-3">
            <div class="h-full bg-gradient-to-r from-primary to-pink-500 transition-all duration-500 rounded-full" style="width: {{ $completion['percentage'] }}%"></div>
        </div>
        @if(count($completion['missing']) > 0)
        <p class="text-xs sm:text-sm text-white/60">
            Lengkapi: <span class="text-primary">{{ implode(', ', $completion['missing']) }}</span>
        </p>
        @else
        <p class="text-xs sm:text-sm text-green-400 flex items-center gap-1">
            <x-icon name="check" class="w-4 h-4" />
            Profile lengkap!
        </p>
        @endif
    </div>
    
    <!-- Profile Info Card -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 sm:mb-6 border border-white/10">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-6">
            <!-- Avatar -->
            <div class="relative flex-shrink-0">
                <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 rounded-full border-2 border-primary/50 overflow-hidden bg-dark">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                </div>
                @if(!$user->avatar)
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary rounded-full border-2 border-dark flex items-center justify-center">
                    <x-icon name="camera" class="w-3 h-3 text-white" />
                </div>
                @endif
            </div>
            
            <!-- User Info -->
            <div class="flex-1 min-w-0 w-full sm:w-auto">
                <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 break-words">{{ $user->name }}</h2>
                <p class="text-sm sm:text-base font-semibold text-white/90 mb-1 break-words">{{ $user->username ?? $user->email }}</p>
                <p class="text-xs sm:text-sm text-white/60 mb-2 break-words">{{ $user->email }}</p>
                @if($user->email_verified_at)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold border border-green-500/30">
                    <x-icon name="check" class="w-3.5 h-3.5" />
                    Email Terverifikasi
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-500/30">
                    <x-icon name="alert" class="w-3.5 h-3.5" />
                    Email belum terverifikasi
                </span>
                @endif
            </div>
            
            <!-- Edit Profile Button -->
            <a href="{{ route('profile.edit') }}" 
               class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold text-sm sm:text-base text-center flex items-center justify-center gap-2">
                <x-icon name="paint" class="w-4 h-4" />
                Edit Profile
            </a>
        </div>
        
        <!-- Additional Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 sm:pt-6 border-t border-white/10">
            <div class="space-y-1">
                <p class="text-xs sm:text-sm text-white/60">Nomor HP</p>
                <p class="font-semibold text-sm sm:text-base">{{ $user->phone ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs sm:text-sm text-white/60">Alamat</p>
                <p class="font-semibold text-sm sm:text-base break-words">{{ $user->address ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs sm:text-sm text-white/60">Role</p>
                <p class="font-semibold text-sm sm:text-base">
                    <span class="px-2 py-1 bg-primary/20 text-primary rounded-lg text-xs">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-xs sm:text-sm text-white/60">Bergabung</p>
                <p class="font-semibold text-sm sm:text-base">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions - Horizontal Grid -->
    <div class="grid grid-cols-3 gap-4">
        <a href="{{ route('profile.edit') }}" 
           class="block glass glass-hover p-6 sm:p-8 rounded-xl transition-all hover:scale-[1.02] border border-white/10 hover:border-primary/40 group">
            <div class="flex flex-col items-center justify-center">
                <div class="relative w-20 h-20 sm:w-24 sm:h-24 mb-4 rounded-xl bg-primary/30 flex items-center justify-center group-hover:bg-primary/40 transition-colors border border-primary/50">
                    <x-icon name="user" class="w-10 h-10 sm:w-12 sm:h-12 text-primary" />
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary rounded-full border-2 border-dark flex items-center justify-center">
                        <x-icon name="paint" class="w-3 h-3 text-white" />
                    </div>
                </div>
                <p class="font-semibold text-base sm:text-lg text-white text-center">Edit Profile</p>
            </div>
        </a>
        <a href="{{ route('wallet.index') }}" 
           class="block glass glass-hover p-6 sm:p-8 rounded-xl transition-all hover:scale-[1.02] border border-white/10 hover:border-green-600/40 group">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mb-4 rounded-xl bg-green-700/30 flex items-center justify-center group-hover:bg-green-700/40 transition-colors border border-green-600/50">
                    <x-icon name="dollar" class="w-10 h-10 sm:w-12 sm:h-12 text-yellow-400" />
                </div>
                <p class="font-semibold text-base sm:text-lg text-white text-center">Wallet</p>
            </div>
        </a>
        <a href="{{ route('orders.index') }}" 
           class="block glass glass-hover p-6 sm:p-8 rounded-xl transition-all hover:scale-[1.02] border border-white/10 hover:border-blue-500/40 group">
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 sm:w-24 sm:h-24 mb-4 rounded-xl bg-blue-600/30 flex items-center justify-center group-hover:bg-blue-600/40 transition-colors border border-blue-500/50">
                    <x-icon name="list" class="w-10 h-10 sm:w-12 sm:h-12 text-blue-300" />
                </div>
                <p class="font-semibold text-base sm:text-lg text-white text-center">Pesanan</p>
            </div>
        </a>
    </div>
</div>
@endsection
