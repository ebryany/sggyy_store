@extends('layouts.app')

@section('title', 'Verifikasi Seller - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="user-check" class="w-6 h-6 sm:w-8 sm:h-8" />
                Verifikasi Seller
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola permintaan verifikasi seller</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
            ← Kembali ke Dashboard
        </a>
    </div>

    <!-- Filters -->
    <div class="glass p-4 sm:p-6 rounded-lg mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.verifications.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('status') === 'pending' || !request('status') ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30' : 'glass glass-hover' }}">
                Pending ({{ \App\Models\SellerVerification::where('status', 'pending')->count() }})
            </a>
            <a href="{{ route('admin.verifications.index', ['status' => 'verified']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('status') === 'verified' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'glass glass-hover' }}">
                Verified ({{ \App\Models\SellerVerification::where('status', 'verified')->count() }})
            </a>
            <a href="{{ route('admin.verifications.index', ['status' => 'rejected']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('status') === 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'glass glass-hover' }}">
                Rejected ({{ \App\Models\SellerVerification::where('status', 'rejected')->count() }})
            </a>
        </div>
    </div>

    <!-- Verifications List -->
    <div class="space-y-4">
        @forelse($verifications as $verification)
        <div class="glass p-4 sm:p-6 rounded-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold">{{ $verification->user->name }}</h3>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($verification->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif($verification->status === 'verified') bg-green-500/20 text-green-400
                            @else bg-red-500/20 text-red-400
                            @endif">
                            {{ strtoupper($verification->status) }}
                        </span>
                    </div>
                    <p class="text-white/60 text-sm mb-1">{{ $verification->user->email }}</p>
                    <p class="text-white/60 text-xs">Dikirim: {{ $verification->created_at->format('d M Y, H:i') }}</p>
                    @if($verification->social_account)
                    <p class="text-white/60 text-xs mt-1">Social: {{ $verification->social_account }}</p>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.verifications.show', $verification) }}" 
                       class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
                        Lihat Detail
                    </a>
                    @if($verification->status === 'pending')
                    <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded-lg text-sm font-semibold transition-all">
                            Approve
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="glass p-8 rounded-lg text-center">
            <x-icon name="user-check" class="w-16 h-16 text-white/20 mx-auto mb-4" />
            <p class="text-white/60">Tidak ada verifikasi seller</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($verifications->hasPages())
    <div class="mt-6">
        {{ $verifications->links() }}
    </div>
    @endif
</div>
@endsection

