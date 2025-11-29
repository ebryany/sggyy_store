@extends('layouts.app')

@section('title', 'Detail Pengguna - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-6xl">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="user" class="w-6 h-6 sm:w-8 sm:h-8" />
                Detail Pengguna
            </h1>
            <p class="text-white/60 text-sm sm:text-base">{{ $user->name }}</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - User Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Profile Card -->
            <div class="glass p-6 rounded-xl border border-white/10">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-6">
                    <div class="relative w-24 h-24 rounded-full border-4 border-primary/50 overflow-hidden bg-gradient-to-br from-primary/20 to-blue-500/20 flex items-center justify-center flex-shrink-0">
                        @php
                            $avatarUrl = null;
                            $hasAvatar = false;
                            if ($user->avatar) {
                                $avatarPath = public_path('storage/' . $user->avatar);
                                if (file_exists($avatarPath)) {
                                    $avatarUrl = asset('storage/' . $user->avatar);
                                    $hasAvatar = true;
                                }
                            }
                        @endphp
                        
                        @if($hasAvatar)
                            <img src="{{ $avatarUrl }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @else
                            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff&size=128' }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        @endif
                        
                        <div class="absolute inset-0 w-full h-full hidden items-center justify-center bg-gradient-to-br from-primary/20 to-blue-500/20">
                            <x-icon name="user" class="w-12 h-12 text-primary/60" />
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-2">{{ $user->name }}</h2>
                        <p class="text-white/60 mb-1">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($user->role === 'admin') bg-red-500/20 text-red-400
                                @elseif($user->role === 'seller') bg-green-500/20 text-green-400
                                @else bg-blue-500/20 text-blue-400
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->email_verified_at)
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold">
                                ✓ Email Terverifikasi
                            </span>
                            @else
                            <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
                                ⚠ Email Belum Terverifikasi
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Details -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-white/10">
                    <div>
                        <p class="text-white/60 text-sm mb-1">Nomor HP</p>
                        <p class="font-semibold">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm mb-1">Alamat</p>
                        <p class="font-semibold break-words">{{ $user->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm mb-1">Saldo Wallet</p>
                        <p class="font-semibold text-primary text-lg">Rp {{ number_format($user->wallet_balance ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm mb-1">Bergabung</p>
                        <p class="font-semibold">{{ $user->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Store Info (if seller) -->
                @if($user->isSeller() && $user->store_name)
                <div class="mt-6 pt-6 border-t border-white/10">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <x-icon name="store" class="w-5 h-5 text-primary" />
                        Informasi Toko
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-white/60 text-sm mb-1">Nama Toko</p>
                            <p class="font-semibold">{{ $user->store_name }}</p>
                        </div>
                        @if($user->store_description)
                        <div>
                            <p class="text-white/60 text-sm mb-1">Deskripsi Toko</p>
                            <p class="font-semibold break-words">{{ $user->store_description }}</p>
                        </div>
                        @endif
                        @if($user->store_slug)
                        <div>
                            <p class="text-white/60 text-sm mb-1">Slug Toko</p>
                            <p class="font-semibold">@{{ $user->store_slug }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="glass p-4 rounded-lg text-center">
                    <p class="text-white/60 text-xs mb-1">Produk</p>
                    <p class="text-2xl font-bold text-primary">{{ $user->products->count() }}</p>
                </div>
                <div class="glass p-4 rounded-lg text-center">
                    <p class="text-white/60 text-xs mb-1">Layanan</p>
                    <p class="text-2xl font-bold text-green-400">{{ $user->services->count() }}</p>
                </div>
                <div class="glass p-4 rounded-lg text-center">
                    <p class="text-white/60 text-xs mb-1">Rating</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ $user->ratings->count() }}</p>
                </div>
                <div class="glass p-4 rounded-lg text-center">
                    <p class="text-white/60 text-xs mb-1">Total</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $user->products->count() + $user->services->count() }}</p>
                </div>
            </div>

            <!-- Products List -->
            @if($user->products->count() > 0)
            <div class="glass p-6 rounded-xl border border-white/10">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="package" class="w-5 h-5 text-primary" />
                    Produk ({{ $user->products->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($user->products->take(5) as $product)
                    <div class="glass glass-hover p-4 rounded-lg border border-white/10">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg bg-white/5 overflow-hidden flex items-center justify-center relative">
                                @if($product->image && $product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->title }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="w-full h-full flex items-center justify-center {{ $product->image ? 'hidden' : '' }}" style="display: {{ $product->image ? 'none' : 'flex' }};">
                                    <x-icon name="package" class="w-6 h-6 text-white/40" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">{{ $product->title }}</p>
                                <p class="text-sm text-white/60">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('products.show', $product->slug ?: $product->id) }}" 
                               class="px-3 py-1.5 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg text-xs font-semibold transition-colors">
                                Lihat
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @if($user->products->count() > 5)
                    <p class="text-center text-white/60 text-sm">Dan {{ $user->products->count() - 5 }} produk lainnya...</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Services List -->
            @if($user->services->count() > 0)
            <div class="glass p-6 rounded-xl border border-white/10">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="shopping-bag" class="w-5 h-5 text-primary" />
                    Layanan ({{ $user->services->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($user->services->take(5) as $service)
                    <div class="glass glass-hover p-4 rounded-lg border border-white/10">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg bg-white/5 overflow-hidden flex items-center justify-center relative">
                                @if($service->image && $service->image_url)
                                <img src="{{ $service->image_url }}" 
                                     alt="{{ $service->title }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif
                                <div class="w-full h-full flex items-center justify-center {{ $service->image ? 'hidden' : '' }}" style="display: {{ $service->image ? 'none' : 'flex' }};">
                                    <x-icon name="shopping-bag" class="w-6 h-6 text-white/40" />
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold truncate">{{ $service->title }}</p>
                                <p class="text-sm text-white/60">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('services.show', $service->slug ?: $service->id) }}" 
                               class="px-3 py-1.5 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg text-xs font-semibold transition-colors">
                                Lihat
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @if($user->services->count() > 5)
                    <p class="text-center text-white/60 text-sm">Dan {{ $user->services->count() - 5 }} layanan lainnya...</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Additional Info -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="glass p-6 rounded-xl border border-white/10">
                <h3 class="text-lg font-semibold mb-4">Statistik Cepat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm">Total Produk</span>
                        <span class="font-semibold">{{ $user->products->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm">Total Layanan</span>
                        <span class="font-semibold">{{ $user->services->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm">Total Rating</span>
                        <span class="font-semibold">{{ $user->ratings->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm">Saldo Wallet</span>
                        <span class="font-semibold text-primary">Rp {{ number_format($user->wallet_balance ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Social Media (if available) -->
            @if($user->social_instagram || $user->social_twitter || $user->social_facebook)
            <div class="glass p-6 rounded-xl border border-white/10">
                <h3 class="text-lg font-semibold mb-4">Media Sosial</h3>
                <div class="space-y-2">
                    @if($user->social_instagram)
                    <div class="flex items-center gap-2">
                        <x-icon name="instagram" class="w-4 h-4 text-pink-400" />
                        <a href="{{ $user->social_instagram }}" target="_blank" class="text-sm text-primary hover:underline">
                            {{ $user->social_instagram }}
                        </a>
                    </div>
                    @endif
                    @if($user->social_twitter)
                    <div class="flex items-center gap-2">
                        <x-icon name="twitter" class="w-4 h-4 text-blue-400" />
                        <a href="{{ $user->social_twitter }}" target="_blank" class="text-sm text-primary hover:underline">
                            {{ $user->social_twitter }}
                        </a>
                    </div>
                    @endif
                    @if($user->social_facebook)
                    <div class="flex items-center gap-2">
                        <x-icon name="facebook" class="w-4 h-4 text-blue-500" />
                        <a href="{{ $user->social_facebook }}" target="_blank" class="text-sm text-primary hover:underline">
                            {{ $user->social_facebook }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Bank Info (if seller) -->
            @if($user->isSeller() && ($user->bank_name || $user->bank_account_number))
            <div class="glass p-6 rounded-xl border border-white/10">
                <h3 class="text-lg font-semibold mb-4">Informasi Bank</h3>
                <div class="space-y-2">
                    @if($user->bank_name)
                    <div>
                        <p class="text-white/60 text-xs mb-1">Nama Bank</p>
                        <p class="font-semibold text-sm">{{ $user->bank_name }}</p>
                    </div>
                    @endif
                    @if($user->bank_account_number)
                    <div>
                        <p class="text-white/60 text-xs mb-1">Nomor Rekening</p>
                        <p class="font-semibold text-sm">{{ $user->bank_account_number }}</p>
                    </div>
                    @endif
                    @if($user->bank_account_name)
                    <div>
                        <p class="text-white/60 text-xs mb-1">Atas Nama</p>
                        <p class="font-semibold text-sm">{{ $user->bank_account_name }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

