@extends('layouts.user')

@section('title', 'Profile Saya - Ebrystoree')

@section('content')
<div class="space-y-3 sm:space-y-6 max-w-4xl mx-auto">
    <!-- Profile Completion Section -->
    <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 mb-4 sm:mb-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm sm:text-base font-semibold text-white/90">Completion</h2>
            <span class="text-lg sm:text-xl font-bold text-primary">{{ $completion['percentage'] }}%</span>
        </div>
        <div class="w-full h-2 bg-white/10 rounded-full overflow-hidden mb-2">
            <div class="h-full bg-primary transition-all duration-500 rounded-full" style="width: {{ $completion['percentage'] }}%"></div>
        </div>
        @if(count($completion['missing']) > 0)
        <p class="text-xs text-white/60">
            Lengkapi: <span class="text-primary font-medium">{{ implode(', ', $completion['missing']) }}</span>
        </p>
        @else
        <p class="text-xs text-green-400 flex items-center gap-1">
            <x-icon name="check" class="w-3.5 h-3.5" />
            Profile lengkap!
        </p>
        @endif
    </div>
    
    <!-- Profile Info Card -->
    <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 mb-4 sm:mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 mb-6">
            <!-- Avatar with Edit Button -->
            <div class="relative flex-shrink-0">
                <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 rounded-full border-2 border-primary/30 overflow-hidden bg-white/5">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="{{ $user->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <!-- Camera Icon for Edit Avatar -->
                <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                </form>
                <label for="avatar-upload" class="absolute -bottom-1 -right-1 w-7 h-7 bg-primary rounded-full border-2 border-dark flex items-center justify-center cursor-pointer hover:bg-primary-dark transition-colors">
                    <x-icon name="camera" class="w-3.5 h-3.5 text-white" />
                </label>
                <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="hidden" form="avatar-form">
            </div>
            
            <!-- User Info -->
            <div class="flex-1 min-w-0 w-full sm:w-auto">
                <h2 class="text-xl sm:text-2xl font-bold mb-1 break-words text-white">{{ $user->name }}</h2>
                <p class="text-sm sm:text-base font-medium text-white/70 mb-1 break-words">{{ $user->username ?? $user->email }}</p>
                <p class="text-xs sm:text-sm text-white/50 mb-2 break-words">{{ $user->email }}</p>
                @if($user->email_verified_at)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">
                    <x-icon name="check" class="w-3.5 h-3.5" />
                    Email Terverifikasi
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">
                    <x-icon name="alert" class="w-3.5 h-3.5" />
                    Email belum terverifikasi
                </span>
                @endif
            </div>
            
            <!-- Edit Profile Button -->
            <a href="{{ route('profile.edit') }}" 
               class="w-full sm:w-auto px-5 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-medium text-sm text-center flex items-center justify-center gap-2">
                <x-icon name="paint" class="w-4 h-4" />
                Edit Profile
            </a>
        </div>
        
        <!-- Additional Info Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 sm:pt-6 border-t border-white/10">
            <div class="space-y-1">
                <p class="text-xs text-white/50">Nomor HP</p>
                <p class="font-semibold text-sm sm:text-base text-white">{{ $user->phone ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs text-white/50">Alamat</p>
                <p class="font-semibold text-sm sm:text-base break-words text-white">{{ $user->address ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-xs text-white/50">Role</p>
                <p class="font-semibold text-sm sm:text-base">
                    <span class="px-2.5 py-1 bg-primary/20 text-primary rounded-lg text-xs font-medium">
                        {{ ucfirst($user->role) }}
                    </span>
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-xs text-white/50">Bergabung</p>
                <p class="font-semibold text-sm sm:text-base text-white">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions - Modern Cards (3 Columns Mobile & Desktop) -->
    <div class="grid grid-cols-3 gap-2 sm:gap-4">
        <!-- Edit Profile Card -->
        <a href="{{ route('profile.edit') }}" 
           class="block glass p-3 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all group">
            <div class="flex flex-col items-center justify-center">
                <div class="relative w-12 h-12 sm:w-20 sm:h-20 mb-2 sm:mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <x-icon name="user" class="w-6 h-6 sm:w-10 sm:h-10 text-primary" />
                    @if(count($completion['missing']) > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 sm:w-5 sm:h-5 bg-primary text-white text-[9px] sm:text-[10px] rounded-full flex items-center justify-center font-bold">
                        1
                    </span>
                    @endif
                </div>
                <p class="font-medium text-xs sm:text-base text-white text-center leading-tight">Edit Profile</p>
            </div>
        </a>
        
        <!-- Wallet Card -->
        <a href="{{ route('wallet.index') }}" 
           class="block glass p-3 sm:p-6 rounded-xl border border-white/5 hover:border-green-500/30 transition-all group">
            <div class="flex flex-col items-center justify-center">
                <div class="w-12 h-12 sm:w-20 sm:h-20 mb-2 sm:mb-3 rounded-lg bg-green-500/10 flex items-center justify-center group-hover:bg-green-500/20 transition-colors">
                    <x-icon name="currency" class="w-6 h-6 sm:w-10 sm:h-10 text-green-400" />
                </div>
                <p class="font-medium text-xs sm:text-base text-white text-center leading-tight">Wallet</p>
            </div>
        </a>
        
        <!-- Pesanan Card -->
        <a href="{{ route('orders.index') }}" 
           class="block glass p-3 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all group">
            <div class="flex flex-col items-center justify-center">
                <div class="w-12 h-12 sm:w-20 sm:h-20 mb-2 sm:mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <x-icon name="list" class="w-6 h-6 sm:w-10 sm:h-10 text-primary" />
                </div>
                <p class="font-medium text-xs sm:text-base text-white text-center leading-tight">Pesanan</p>
            </div>
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar-upload');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const form = document.getElementById('avatar-form');
                const formData = new FormData();
                formData.append('avatar', e.target.files[0]);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response;
                    }
                    throw new Error('Network response was not ok');
                })
                .then(() => {
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengupload avatar');
                });
            }
        });
    }
});
</script>
@endpush
@endsection
