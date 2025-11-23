@extends('layouts.app')

@section('title', 'Detail Penarikan Saldo - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="wallet" class="w-6 h-6 sm:w-8 sm:h-8" />
                Detail Penarikan Saldo
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Referensi: {{ $withdrawal->reference_number }}</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all flex items-center gap-2">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Kembali
            </a>
        </div>
    </div>

    <!-- Withdrawal Details -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="glass p-6 rounded-xl border-2 
            @if($withdrawal->status === 'completed') border-green-500/30 bg-green-500/5
            @elseif($withdrawal->status === 'processing') border-blue-500/30 bg-blue-500/5
            @elseif($withdrawal->status === 'rejected') border-red-500/30 bg-red-500/5
            @else border-yellow-500/30 bg-yellow-500/5
            @endif">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm mb-1">Status</p>
                    <p class="text-2xl font-bold 
                        @if($withdrawal->status === 'completed') text-green-400
                        @elseif($withdrawal->status === 'processing') text-blue-400
                        @elseif($withdrawal->status === 'rejected') text-red-400
                        @else text-yellow-400
                        @endif">
                        @if($withdrawal->status === 'completed') SELESAI
                        @elseif($withdrawal->status === 'processing') DIPROSES
                        @elseif($withdrawal->status === 'rejected') DITOLAK
                        @else MENUNGGU
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-sm mb-1">Jumlah</p>
                    <p class="text-3xl font-bold text-primary">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Penjual -->
        <div class="glass p-6 rounded-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <x-icon name="user" class="w-5 h-5" />
                Informasi Penjual
            </h2>
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $withdrawal->seller->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($withdrawal->seller->name) }}" 
                     alt="{{ $withdrawal->seller->name }}" 
                     class="w-16 h-16 rounded-full border-2 border-primary/50">
                <div>
                    <p class="text-xl font-bold">{{ $withdrawal->seller->name }}</p>
                    <p class="text-white/60">{{ $withdrawal->seller->email }}</p>
                </div>
            </div>
        </div>

        <!-- Detail Penarikan -->
        <div class="glass p-6 rounded-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <x-icon name="file-text" class="w-5 h-5" />
                Detail Penarikan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-white/60 text-sm mb-1">Nomor Referensi</p>
                    <p class="font-semibold">{{ $withdrawal->reference_number }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-sm mb-1">Metode Penarikan</p>
                    <p class="font-semibold flex items-center gap-2">
                        @if($withdrawal->method === 'bank_transfer')
                            <x-icon name="bank" class="w-4 h-4" />
                            Transfer Bank
                        @else
                            <x-icon name="mobile" class="w-4 h-4" />
                            Dompet Digital
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-white/60 text-sm mb-1">Tanggal Permintaan</p>
                    <p class="font-semibold">{{ $withdrawal->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($withdrawal->processed_at)
                <div>
                    <p class="text-white/60 text-sm mb-1">Tanggal Diproses</p>
                    <p class="font-semibold">{{ $withdrawal->processed_at->format('d M Y, H:i') }}</p>
                </div>
                @endif
                @if($withdrawal->processor)
                <div>
                    <p class="text-white/60 text-sm mb-1">Diproses Oleh</p>
                    <p class="font-semibold">{{ $withdrawal->processor->name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Informasi Rekening -->
        <div class="glass p-6 rounded-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <x-icon name="bank" class="w-5 h-5" />
                Informasi Rekening
            </h2>
            <div class="space-y-3">
                <div>
                    <p class="text-white/60 text-sm mb-1">Nama Bank / Dompet Digital</p>
                    <p class="font-semibold text-lg">{{ $withdrawal->bank_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-sm mb-1">Nomor Rekening / Dompet Digital</p>
                    <p class="font-semibold text-lg">{{ $withdrawal->account_number ?? '-' }}</p>
                </div>
                @if($withdrawal->account_name)
                <div>
                    <p class="text-white/60 text-sm mb-1">Atas Nama</p>
                    <p class="font-semibold text-lg">{{ $withdrawal->account_name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Alasan Penolakan (jika ditolak) -->
        @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
        <div class="glass p-6 rounded-xl border-2 border-red-500/30 bg-red-500/5">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-red-400">
                <x-icon name="alert-circle" class="w-5 h-5" />
                Alasan Penolakan
            </h2>
            <p class="text-white/90">{{ $withdrawal->rejection_reason }}</p>
        </div>
        @endif

        <!-- Aksi -->
        @if(in_array($withdrawal->status, ['pending', 'processing']))
        <div class="glass p-6 rounded-xl border border-white/10">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <x-icon name="settings" class="w-5 h-5" />
                Aksi
            </h2>
            <div class="flex flex-wrap gap-3">
                @if($withdrawal->status === 'pending')
                <button type="button"
                        onclick="
                            const modal = document.getElementById('approve-withdrawal-modal');
                            if (modal) {
                                modal.style.display = 'flex';
                                document.body.style.overflow = 'hidden';
                            }
                        "
                        class="px-6 py-3 glass glass-hover rounded-lg border border-blue-500/30 bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 hover:border-blue-500/50 transition-all font-semibold flex items-center gap-2 shadow-lg hover:shadow-blue-500/20">
                    <x-icon name="check" class="w-5 h-5" />
                    Setujui (Diproses)
                </button>
                
                <!-- Modal Konfirmasi Setujui -->
                <x-confirm-modal 
                    id="approve-withdrawal-modal"
                    title="Setujui Penarikan Saldo"
                    message="Setujui penarikan saldo ini? Status akan berubah menjadi Diproses."
                    confirm-text="Ya, Setujui"
                    cancel-text="Batal"
                    type="info" />
                
                <form id="approve-withdrawal-form" method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" style="display: none;">
                    @csrf
                </form>
                @endif
                
                @if(in_array($withdrawal->status, ['pending', 'processing']))
                <button type="button"
                        onclick="
                            const modal = document.getElementById('complete-withdrawal-modal');
                            if (modal) {
                                modal.style.display = 'flex';
                                document.body.style.overflow = 'hidden';
                            }
                        "
                        class="px-6 py-3 glass glass-hover rounded-lg border border-green-500/30 bg-green-500/20 text-green-400 hover:bg-green-500/30 hover:border-green-500/50 transition-all font-semibold flex items-center gap-2 shadow-lg hover:shadow-green-500/20">
                    <x-icon name="check" class="w-5 h-5" />
                    Tandai Selesai
                </button>
                
                <!-- Modal Konfirmasi Selesai -->
                <x-confirm-modal 
                    id="complete-withdrawal-modal"
                    title="Tandai Sebagai Selesai"
                    message="Tandai sebagai selesai? Pastikan transfer sudah dilakukan ke rekening penjual."
                    confirm-text="Ya, Tandai Selesai"
                    cancel-text="Batal"
                    type="info" />
                
                <form id="complete-withdrawal-form" method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}" style="display: none;">
                    @csrf
                </form>
                @endif

                @if(in_array($withdrawal->status, ['pending', 'processing']))
                <button type="button" 
                        onclick="
                            const modal = document.getElementById('reject-modal');
                            if (modal) {
                                modal.style.display = 'flex';
                                document.body.style.overflow = 'hidden';
                            }
                        "
                        class="px-6 py-3 glass glass-hover rounded-lg border border-red-500/30 bg-red-500/20 text-red-400 hover:bg-red-500/30 hover:border-red-500/50 transition-all font-semibold flex items-center gap-2 shadow-lg hover:shadow-red-500/20">
                    <x-icon name="x" class="w-5 h-5" />
                    Tolak
                </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Modal Tolak -->
        @if(in_array($withdrawal->status, ['pending', 'processing']))
        <div id="reject-modal" 
             style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             onclick="if(event.target === this) this.style.display='none'">
            <div class="glass p-6 rounded-xl border border-white/10 max-w-md w-full mx-4" onclick="event.stopPropagation()">
                <h3 class="text-xl font-bold mb-4">Tolak Penarikan Saldo</h3>
                <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Alasan Penolakan *</label>
                        <textarea name="rejection_reason" 
                                  rows="4"
                                  required
                                  minlength="10"
                                  maxlength="500"
                                  placeholder="Masukkan alasan penolakan (min 10 karakter)"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                        <p class="text-xs text-white/60 mt-1">Minimal 10 karakter, maksimal 500 karakter</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 glass glass-hover rounded-lg border border-red-500/30 bg-red-500/20 text-red-400 hover:bg-red-500/30 hover:border-red-500/50 transition-all font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-red-500/20">
                            <x-icon name="x" class="w-5 h-5" />
                            Tolak
                        </button>
                        <button type="button" 
                                onclick="document.getElementById('reject-modal').style.display='none'"
                                class="px-6 py-3 glass glass-hover rounded-lg transition-colors font-semibold">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve withdrawal
    const approveBtn = document.getElementById('approve-withdrawal-modal-confirm-btn');
    const approveModal = document.getElementById('approve-withdrawal-modal');
    const approveForm = document.getElementById('approve-withdrawal-form');
    
    if (approveBtn && approveModal && approveForm) {
        approveBtn.addEventListener('click', function() {
            approveModal.style.display = 'none';
            document.body.style.overflow = '';
            approveForm.submit();
        });
    }
    
    // Complete withdrawal
    const completeBtn = document.getElementById('complete-withdrawal-modal-confirm-btn');
    const completeModal = document.getElementById('complete-withdrawal-modal');
    const completeForm = document.getElementById('complete-withdrawal-form');
    
    if (completeBtn && completeModal && completeForm) {
        completeBtn.addEventListener('click', function() {
            completeModal.style.display = 'none';
            document.body.style.overflow = '';
            completeForm.submit();
        });
    }
});
</script>
@endsection







