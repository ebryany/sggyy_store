@extends('layouts.app')

@section('title', 'Detail Verifikasi Seller - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="user-check" class="w-6 h-6 sm:w-8 sm:h-8" />
                Detail Verifikasi Seller
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Review permintaan verifikasi seller</p>
        </div>
        <a href="{{ route('admin.verifications.index') }}" 
           class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
            ← Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Info -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Informasi User</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-white/60 text-sm">Nama</p>
                        <p class="font-semibold">{{ $verification->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm">Email</p>
                        <p class="font-semibold">{{ $verification->user->email }}</p>
                    </div>
                    @if($verification->user->phone)
                    <div>
                        <p class="text-white/60 text-sm">Phone</p>
                        <p class="font-semibold">{{ $verification->user->phone }}</p>
                    </div>
                    @endif
                    @if($verification->social_account)
                    <div>
                        <p class="text-white/60 text-sm">Social Account</p>
                        <p class="font-semibold">{{ $verification->social_account }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Dokumen</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($verification->ktp_path)
                    <div>
                        <p class="text-white/60 text-sm mb-2">KTP</p>
                        <a href="{{ asset('storage/' . $verification->ktp_path) }}" target="_blank" 
                           class="block glass glass-hover p-4 rounded-lg hover:scale-105 transition-all">
                            <img src="{{ asset('storage/' . $verification->ktp_path) }}" 
                                 alt="KTP" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </a>
                    </div>
                    @endif
                    @if($verification->photo_path)
                    <div>
                        <p class="text-white/60 text-sm mb-2">Foto</p>
                        <a href="{{ asset('storage/' . $verification->photo_path) }}" target="_blank" 
                           class="block glass glass-hover p-4 rounded-lg hover:scale-105 transition-all">
                            <img src="{{ asset('storage/' . $verification->photo_path) }}" 
                                 alt="Foto" 
                                 class="w-full h-48 object-cover rounded-lg">
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Status</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-white/60 text-sm">Status</p>
                        <span class="px-3 py-1 rounded text-sm font-semibold
                            @if($verification->status === 'pending') bg-yellow-500/20 text-yellow-400
                            @elseif($verification->status === 'verified') bg-green-500/20 text-green-400
                            @else bg-red-500/20 text-red-400
                            @endif">
                            {{ strtoupper($verification->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-white/60 text-sm">Dikirim</p>
                        <p class="font-semibold">{{ $verification->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($verification->verified_at)
                    <div>
                        <p class="text-white/60 text-sm">Diverifikasi</p>
                        <p class="font-semibold">{{ $verification->verified_at->format('d M Y, H:i') }}</p>
                        @if($verification->verifier)
                        <p class="text-white/60 text-xs">oleh {{ $verification->verifier->name }}</p>
                        @endif
                    </div>
                    @endif
                    @if($verification->rejection_reason)
                    <div>
                        <p class="text-white/60 text-sm">Alasan Penolakan</p>
                        <p class="font-semibold text-red-400">{{ $verification->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
            @if($verification->status === 'pending')
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Actions</h2>
                <div class="space-y-3">
                    <form action="{{ route('admin.verifications.approve', $verification) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded-lg font-semibold transition-all">
                            ✅ Approve
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST" id="rejectForm">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-white/60 text-sm mb-2">Alasan Penolakan</label>
                            <textarea name="rejection_reason" 
                                      required
                                      rows="3"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-red-500"
                                      placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg font-semibold transition-all">
                            ❌ Reject
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- User Actions -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.show', $verification->user) }}" 
                       class="block px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold text-center hover:scale-105 transition-all">
                        Lihat Profil User
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

