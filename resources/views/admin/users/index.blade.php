@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="users" class="w-6 h-6 sm:w-8 sm:h-8" />
                Manajemen Pengguna
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola semua pengguna, seller, dan admin platform</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="glass p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Total Pengguna</p>
            <p class="text-2xl font-bold text-primary">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="glass p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Admin</p>
            <p class="text-2xl font-bold text-red-400">{{ number_format($stats['admins']) }}</p>
        </div>
        <div class="glass p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Seller</p>
            <p class="text-2xl font-bold text-green-400">{{ number_format($stats['sellers']) }}</p>
        </div>
        <div class="glass p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">User</p>
            <p class="text-2xl font-bold text-blue-400">{{ number_format($stats['users']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass p-4 sm:p-6 rounded-lg mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau toko..."
                   class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
            <select name="role" 
                    class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary focus:bg-white/10">
                <option value="" class="bg-dark text-white">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }} class="bg-dark text-white">Admin</option>
                <option value="seller" {{ request('role') === 'seller' ? 'selected' : '' }} class="bg-dark text-white">Seller</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }} class="bg-dark text-white">User</option>
            </select>
            <button type="submit" 
                    class="px-6 py-2 bg-primary hover:bg-primary/90 rounded-lg font-semibold transition-colors">
                Cari
            </button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-2 glass glass-hover rounded-lg font-semibold transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="glass rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white/60 uppercase">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white/60 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white/60 uppercase">Wallet</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white/60 uppercase">Bergabung</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white/60 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="relative w-10 h-10 rounded-full border-2 border-primary/50 overflow-hidden bg-gradient-to-br from-primary/20 to-blue-500/20 flex items-center justify-center flex-shrink-0">
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
                                        <x-icon name="user" class="w-5 h-5 text-primary/60" />
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold">{{ $user->name }}</p>
                                    <p class="text-xs text-white/60">{{ $user->email }}</p>
                                    @if($user->store_name)
                                    <p class="text-xs text-primary">{{ $user->store_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($user->role === 'admin') bg-red-500/20 text-red-400
                                @elseif($user->role === 'seller') bg-green-500/20 text-green-400
                                @else bg-blue-500/20 text-blue-400
                                @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-semibold">Rp {{ number_format($user->wallet_balance ?? 0, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm text-white/60">{{ $user->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="px-3 py-1.5 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg text-xs font-semibold transition-colors">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-white/60">
                            Tidak ada pengguna ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection

