@extends('layouts.app')

@section('title', 'Kelola Pembayaran - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="credit-card" class="w-8 h-8 sm:w-10 sm:h-10" />
                Kelola Pembayaran
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola dan verifikasi transaksi pembayaran</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-wrap items-center gap-3 sm:gap-4">
            <select name="status" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                Filter
            </button>
        </form>
    </div>
    
    <!-- Payments Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <h2 class="text-xl sm:text-2xl font-semibold mb-4">Daftar Transaksi Pembayaran</h2>
        
        @if($payments->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">ID Pesanan</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Pembeli</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Produk/Layanan</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Jumlah</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Metode</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Bukti</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <a href="{{ route('orders.show', $payment->order) }}" 
                               class="font-mono text-sm text-primary hover:underline" target="_blank">
                                #{{ $payment->order->id }}
                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $payment->order->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->order->user->name) }}" 
                                     alt="{{ $payment->order->user->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold">{{ $payment->order->user->name }}</p>
                                    <p class="text-xs text-white/60">{{ $payment->order->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">
                                @if($payment->order->product)
                                    <p class="font-semibold truncate max-w-[150px]">{{ $payment->order->product->title }}</p>
                                    <p class="text-white/60 text-xs">Produk</p>
                                @elseif($payment->order->service)
                                    <p class="font-semibold truncate max-w-[150px]">{{ $payment->order->service->title }}</p>
                                    <p class="text-white/60 text-xs">Layanan</p>
                                @else
                                    <p class="text-white/60 text-xs">-</p>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-base sm:text-lg font-bold text-primary">Rp {{ number_format($payment->order->total, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <div class="flex items-center gap-2">
                                <x-icon name="{{ $payment->getMethodIcon() }}" class="w-4 h-4" />
                                <div class="flex flex-col">
                                    <span>{{ $payment->getMethodDisplayName() }}</span>
                                    @if($payment->isAutoVerified())
                                        <span class="text-xs text-green-400 flex items-center gap-1">
                                            <x-icon name="check-circle" class="w-3 h-3" />
                                            Auto Verify
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($payment->proof_path)
                            <button type="button"
                                    onclick="
                                        const modal = document.getElementById('proof-modal-{{ $payment->id }}');
                                        if (modal) {
                                            modal.style.display = 'flex';
                                            document.body.style.overflow = 'hidden';
                                        }
                                    "
                                    class="text-primary hover:underline text-xs sm:text-sm font-semibold transition-colors cursor-pointer flex items-center gap-1">
                                <x-icon name="camera" class="w-4 h-4" />
                                Lihat Bukti
                            </button>
                            @else
                            <span class="text-white/40 text-xs sm:text-sm">Belum ada</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($payment->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Menunggu</span>
                            @elseif($payment->status === 'verified')
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Terverifikasi</span>
                            @else
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs">Ditolak</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-white/60 text-xs sm:text-sm">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="py-3 px-4" style="position: relative; z-index: 1;">
                            @if($payment->status === 'pending')
                            <div class="flex flex-col gap-2" style="position: relative; z-index: 1;">
                                <button type="button"
                                        class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded text-xs hover:bg-blue-500/30 whitespace-nowrap transition-colors font-semibold cursor-pointer flex items-center gap-1"
                                        style="position: relative; z-index: 2; pointer-events: auto;"
                                        onclick="
                                            const modal = document.getElementById('detail-modal-{{ $payment->id }}');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        ">
                                    <x-icon name="eye" class="w-4 h-4" />
                                    Lihat Detail
                                </button>
                                @if($payment->isAutoVerified())
                                    <div class="px-3 py-1.5 bg-blue-500/10 border border-blue-500/30 rounded text-xs text-blue-400 flex items-center gap-1">
                                        <x-icon name="clock" class="w-4 h-4" />
                                        Verifikasi Otomatis via Webhook
                                    </div>
                                @else
                                <div class="flex gap-1" style="position: relative; z-index: 2;">
                                    <button type="button"
                                            class="px-3 py-1.5 bg-green-500/20 text-green-400 rounded text-xs hover:bg-green-500/30 active:bg-green-500/40 whitespace-nowrap transition-colors font-semibold cursor-pointer flex items-center gap-1"
                                            onclick="
                                                const modal = document.getElementById('verify-payment-modal-{{ $payment->id }}');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            ">
                                        <x-icon name="check" class="w-4 h-4" />
                                        Verifikasi
                                    </button>
                                    
                                    <button type="button"
                                            class="px-3 py-1.5 bg-red-500/20 text-red-400 rounded text-xs hover:bg-red-500/30 active:bg-red-500/40 whitespace-nowrap transition-colors font-semibold cursor-pointer flex items-center gap-1"
                                            onclick="
                                                const modal = document.getElementById('reject-modal-{{ $payment->id }}');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            ">
                                        <x-icon name="x" class="w-4 h-4" />
                                        Tolak
                                    </button>
                                </div>
                                @endif
                            </div>
                            @elseif($payment->verifier)
                            <p class="text-xs text-white/60">
                                Oleh: {{ $payment->verifier->name }}<br>
                                @if($payment->verified_at)
                                {{ $payment->verified_at->format('d M Y, H:i') }}
                                @endif
                            </p>
                            @endif
                        </td>
                    </tr>
                    
                    <!-- Detail Modal (untuk lihat semua info sebelum verify) -->
                    @if($payment->status === 'pending')
                    <div id="detail-modal-{{ $payment->id }}"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                             onclick="event.stopPropagation()">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                                    <x-icon name="document" class="w-6 h-6" />
                                    Detail Pembayaran & Data Pembeli
                                </h3>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('detail-modal-{{ $payment->id }}');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="text-white/60 hover:text-white transition-colors p-1 hover:bg-white/10 rounded cursor-pointer">
                                    <x-icon name="x" class="w-6 h-6" />
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- User Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary">👤 Informasi User</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-white/60">Nama</p>
                                            <p class="font-semibold">{{ $payment->order->user->name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">Email</p>
                                            <p class="font-semibold">{{ $payment->order->user->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">User ID</p>
                                            <p class="font-semibold">#{{ $payment->order->user->id }}</p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">Role</p>
                                            <p class="font-semibold">{{ ucfirst($payment->order->user->role) }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary">📦 Informasi Pesanan</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Nomor Pesanan</span>
                                            <span class="font-semibold">{{ $payment->order->order_number }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Jenis</span>
                                            <span class="font-semibold">{{ $payment->order->type === 'product' ? 'Produk' : 'Layanan' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Item</span>
                                            <span class="font-semibold">
                                                @if($payment->order->product)
                                                    {{ $payment->order->product->title }}
                                                @elseif($payment->order->service)
                                                    {{ $payment->order->service->title }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Total</span>
                                            <span class="font-semibold text-primary text-lg">Rp {{ number_format($payment->order->total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Status Pesanan</span>
                                            <span class="font-semibold">
                                                @if($payment->order->status === 'pending') Menunggu
                                                @elseif($payment->order->status === 'paid') Dibayar
                                                @elseif($payment->order->status === 'processing') Diproses
                                                @elseif($payment->order->status === 'completed') Selesai
                                                @elseif($payment->order->status === 'cancelled') Dibatalkan
                                                @else {{ ucfirst($payment->order->status) }}
                                                @endif
                                            </span>
                                        </div>
                                        @if($payment->order->notes)
                                        <div>
                                            <p class="text-white/60 mb-1">Catatan</p>
                                            <p class="font-semibold">{{ $payment->order->notes }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Payment Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary flex items-center gap-2">
                                        <x-icon name="credit-card" class="w-5 h-5" />
                                        Informasi Pembayaran
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-white/60">ID Pembayaran</span>
                                            <span class="font-semibold">#{{ $payment->id }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Metode</span>
                                            <span class="font-semibold">{{ $payment->getMethodDisplayName() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Status</span>
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Menunggu</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Tanggal Pembayaran</span>
                                            <span class="font-semibold">{{ $payment->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Proof -->
                                @if($payment->proof_path)
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary flex items-center gap-2">
                                        <x-icon name="camera" class="w-5 h-5" />
                                        Bukti Pembayaran
                                    </h4>
                                    @php
                                        $proofUrl = $payment->getProofUrl();
                                    @endphp
                                    
                                    @if($payment->isProofImage())
                                    <div class="mb-3">
                                        <img src="{{ $proofUrl }}" 
                                             alt="Payment Proof" 
                                             class="w-full rounded-lg border-2 border-white/10 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="window.open('{{ $proofUrl }}', '_blank')"
                                             title="Klik untuk membuka di tab baru">
                                    </div>
                                    <a href="{{ $proofUrl }}" 
                                       target="_blank" 
                                       class="text-primary hover:underline text-sm flex items-center gap-1">
                                        <x-icon name="link" class="w-4 h-4" />
                                        Buka di tab baru
                                    </a>
                                    @elseif($payment->isProofPdf())
                                    <div class="mb-3 p-3 bg-white/5 rounded-lg">
                                        <div class="flex items-center gap-3 mb-3">
                                            <x-icon name="file-text" class="w-8 h-8 text-primary" />
                                            <div class="flex-1">
                                                <p class="font-semibold">File PDF</p>
                                                <p class="text-xs text-white/60">{{ basename($payment->proof_path) }}</p>
                                            </div>
                                        </div>
                                        <iframe src="{{ $proofUrl }}" 
                                                class="w-full h-64 rounded-lg border-2 border-white/10"
                                                frameborder="0">
                                        </iframe>
                                    </div>
                                    <a href="{{ $proofUrl }}" 
                                       target="_blank" 
                                       class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center text-sm font-semibold transition-colors">
                                        📥 Download / Buka PDF
                                    </a>
                                    @else
                                    <div class="mb-3">
                                        <a href="{{ $proofUrl }}" 
                                           target="_blank" 
                                           class="flex items-center gap-3 p-3 bg-primary/20 hover:bg-primary/30 rounded-lg transition-colors">
                                            <span class="text-2xl">📎</span>
                                            <div class="flex-1">
                                                <p class="font-semibold">Bukti Pembayaran</p>
                                                <p class="text-xs text-white/60">{{ basename($payment->proof_path) }}</p>
                                            </div>
                                            <span class="text-primary">→</span>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="glass p-4 rounded-lg border-2 border-yellow-500/30">
                                    <p class="text-yellow-400 font-semibold">⚠️ Belum ada bukti pembayaran yang diupload</p>
                                </div>
                                @endif
                                
                                <!-- Actions -->
                                <div class="flex gap-3 pt-4 border-t border-white/10">
                                    <a href="{{ route('orders.show', $payment->order) }}" 
                                       target="_blank"
                                       class="flex-1 px-4 py-2 glass glass-hover rounded-lg text-center flex items-center justify-center gap-2">
                                        <x-icon name="document" class="w-4 h-4" />
                                        Lihat Detail Pesanan
                                    </a>
                                    @if($payment->proof_path)
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('verify-payment-simple-modal-{{ $payment->id }}');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            "
                                            class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg transition-colors font-semibold cursor-pointer flex items-center justify-center gap-2">
                                        <x-icon name="check" class="w-5 h-5" />
                                        Verifikasi Pembayaran
                                    </button>
                                    
                                    <form id="verify-payment-simple-form-{{ $payment->id }}" method="POST" action="{{ route('admin.payments.verify', $payment) }}" style="display: none;">
                                        @csrf
                                    </form>
                                    
                                    <!-- Verify Payment Simple Modal -->
                                    <x-confirm-modal 
                                        id="verify-payment-simple-modal-{{ $payment->id }}"
                                        title="Verifikasi Pembayaran"
                                        message="Yakin verifikasi pembayaran ini?"
                                        confirm-text="Ya, Verifikasi"
                                        cancel-text="Batal"
                                        type="info"
                                        formId="verify-payment-simple-form-{{ $payment->id }}" />
                                    @endif
                                    <button type="button"
                                            onclick="
                                                const detailModal = document.getElementById('detail-modal-{{ $payment->id }}');
                                                if (detailModal) {
                                                    detailModal.style.display = 'none';
                                                }
                                                document.body.style.overflow = '';
                                                setTimeout(() => {
                                                    const rejectModal = document.getElementById('reject-modal-{{ $payment->id }}');
                                                    if (rejectModal) {
                                                        rejectModal.style.display = 'flex';
                                                        document.body.style.overflow = 'hidden';
                                                    }
                                                }, 100);
                                            "
                                            class="flex-1 px-4 py-2 bg-red-500/20 text-red-400 hover:bg-red-500/30 rounded-lg transition-colors font-semibold cursor-pointer flex items-center justify-center gap-2">
                                        <x-icon name="x" class="w-5 h-5" />
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Proof Modal (Standalone) -->
                    @if($payment->proof_path)
                    <div id="proof-modal-{{ $payment->id }}"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                             onclick="event.stopPropagation()">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <h3 class="text-xl font-semibold flex items-center gap-2">
                                    <x-icon name="camera" class="w-6 h-6" />
                                    Bukti Pembayaran - ID #{{ $payment->id }}
                                </h3>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('proof-modal-{{ $payment->id }}');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="text-white/60 hover:text-white transition-colors p-1 hover:bg-white/10 rounded cursor-pointer">
                                    <x-icon name="x" class="w-6 h-6" />
                                </button>
                            </div>
                            <div class="mb-4">
                                <p class="text-white/60 text-sm mb-2">
                                    Pesanan: <a href="{{ route('orders.show', $payment->order) }}" target="_blank" class="text-primary hover:underline">{{ $payment->order->order_number }}</a> | 
                                    Pembeli: {{ $payment->order->user->name }} ({{ $payment->order->user->email }})
                                </p>
                            </div>
                            <div class="mb-4 bg-black/20 p-2 rounded-lg">
                                @php
                                    $proofUrl = $payment->getProofUrl();
                                @endphp
                                
                                @if($payment->isProofImage())
                                <img src="{{ $proofUrl }}" 
                                     alt="Payment Proof" 
                                     class="w-full rounded-lg border-2 border-white/20 cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="window.open('{{ $proofUrl }}', '_blank')"
                                     title="Klik untuk membuka di tab baru">
                                @elseif($payment->isProofPdf())
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                                        <span class="text-3xl">📄</span>
                                        <div class="flex-1">
                                            <p class="font-semibold">File PDF</p>
                                            <p class="text-xs text-white/60">{{ basename($payment->proof_path) }}</p>
                                        </div>
                                    </div>
                                    <iframe src="{{ $proofUrl }}" 
                                            class="w-full h-96 rounded-lg border-2 border-white/20"
                                            frameborder="0">
                                    </iframe>
                                </div>
                                @else
                                <div class="p-4 text-center">
                                    <span class="text-4xl block mb-2">📎</span>
                                    <p class="font-semibold mb-1">File Bukti Pembayaran</p>
                                    <p class="text-sm text-white/60">{{ basename($payment->proof_path) }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ $proofUrl }}" 
                                   target="_blank" 
                                   class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center transition-colors cursor-pointer flex items-center justify-center gap-2">
                                    <x-icon name="link" class="w-4 h-4" />
                                    Buka di Tab Baru (Ukuran Penuh)
                                </a>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('proof-modal-{{ $payment->id }}');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="px-4 py-2 glass glass-hover rounded-lg transition-colors cursor-pointer">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Verify Payment Modal -->
                    @if($payment->status === 'pending')
                    <div id="verify-payment-modal-{{ $payment->id }}"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-md w-full mx-4 shadow-2xl"
                             onclick="event.stopPropagation()">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="flex-shrink-0">
                                    <x-icon name="info" class="w-8 h-8 text-blue-400" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-2">Verifikasi Pembayaran</h3>
                                    <p class="text-white/80 text-sm sm:text-base">Yakin verifikasi pembayaran ini? Pastikan sudah cek bukti pembayaran dan detail user!</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                                @csrf
                                <div class="flex space-x-3">
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('verify-payment-modal-{{ $payment->id }}');
                                                if (modal) {
                                                    modal.style.display = 'none';
                                                    document.body.style.overflow = '';
                                                }
                                            "
                                            class="flex-1 px-4 py-2.5 glass glass-hover rounded-lg font-semibold transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors font-semibold text-white cursor-pointer">
                                        Ya, Verifikasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Reject Modal -->
                    @if($payment->status === 'pending')
                    <div id="reject-modal-{{ $payment->id }}"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-md w-full mx-4 shadow-2xl"
                             onclick="event.stopPropagation()">
                            <h3 class="text-xl font-semibold mb-4">Tolak Pembayaran</h3>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Alasan Penolakan *</label>
                                    <textarea name="rejection_reason" rows="4" required
                                              class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                                        Tolak
                                    </button>
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('reject-modal-{{ $payment->id }}');
                                                if (modal) {
                                                    modal.style.display = 'none';
                                                    document.body.style.overflow = '';
                                                }
                                            "
                                            class="flex-1 px-4 py-2 glass glass-hover rounded-lg transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-white/60 text-sm sm:text-base">Tidak ada payment dengan status ini.</p>
        </div>
        @endif
    </div>
</div>

@endsection
