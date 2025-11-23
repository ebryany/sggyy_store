@extends('layouts.app')

@section('title', 'Kelola Top-Up Wallet - Admin - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="wallet" class="w-8 h-8 sm:w-10 sm:h-10" />
                Kelola Top-Up Wallet
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Verifikasi dan kelola permintaan top-up saldo wallet</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all flex items-center gap-2">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="glass p-4 rounded-lg border border-yellow-500/30">
            <div class="flex items-center justify-between mb-2">
                <x-icon name="clock" class="w-6 h-6 text-yellow-400" />
                <span class="text-2xl font-bold text-yellow-400">{{ $stats['total_pending'] }}</span>
            </div>
            <p class="text-sm text-white/60">Pending</p>
            <p class="text-xs text-white/40 mt-1">Rp {{ number_format($stats['total_pending_amount'], 0, ',', '.') }}</p>
        </div>
        
        <div class="glass p-4 rounded-lg border border-blue-500/30">
            <div class="flex items-center justify-between mb-2">
                <x-icon name="check-circle" class="w-6 h-6 text-blue-400" />
                <span class="text-2xl font-bold text-blue-400">{{ $stats['total_approved'] }}</span>
            </div>
            <p class="text-sm text-white/60">Approved</p>
        </div>
        
        <div class="glass p-4 rounded-lg border border-green-500/30">
            <div class="flex items-center justify-between mb-2">
                <x-icon name="check" class="w-6 h-6 text-green-400" />
                <span class="text-2xl font-bold text-green-400">{{ $stats['total_completed'] }}</span>
            </div>
            <p class="text-sm text-white/60">Completed</p>
        </div>
        
        <div class="glass p-4 rounded-lg border border-red-500/30">
            <div class="flex items-center justify-between mb-2">
                <x-icon name="x" class="w-6 h-6 text-red-400" />
                <span class="text-2xl font-bold text-red-400">{{ $stats['total_rejected'] }}</span>
            </div>
            <p class="text-sm text-white/60">Rejected</p>
        </div>
        
        <div class="glass p-4 rounded-lg border border-primary/30">
            <div class="flex items-center justify-between mb-2">
                <x-icon name="list" class="w-6 h-6 text-primary" />
                <span class="text-2xl font-bold text-primary">{{ $transactions->total() }}</span>
            </div>
            <p class="text-sm text-white/60">Total</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.wallet.index') }}" class="flex flex-wrap items-center gap-3 sm:gap-4">
            <select name="status" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }} class="bg-dark text-white">Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }} class="bg-dark text-white">Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }} class="bg-dark text-white">Approved</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }} class="bg-dark text-white">Completed</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }} class="bg-dark text-white">Rejected</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                <x-icon name="filter" class="w-4 h-4" />
                Filter
            </button>
            @if($status !== 'pending')
            <a href="{{ route('admin.wallet.index') }}" class="px-6 py-2 glass glass-hover rounded-lg font-semibold flex items-center gap-2 border border-white/10">
                <x-icon name="refresh" class="w-4 h-4" />
                Reset
            </a>
            @endif
        </form>
    </div>
    
    <!-- Transactions Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <h2 class="text-xl sm:text-2xl font-semibold mb-4 flex items-center gap-2">
            <x-icon name="list" class="w-5 h-5" />
            <span>Daftar Permintaan Top-Up</span>
        </h2>
        
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Reference</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">User</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Jumlah</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Metode</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Bukti</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4">
                            <span class="font-mono text-xs sm:text-sm text-white/80">{{ $transaction->reference_number }}</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <img src="{{ $transaction->user->avatar ? asset('storage/' . $transaction->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($transaction->user->name) }}" 
                                     alt="{{ $transaction->user->name }}" 
                                     class="w-8 h-8 rounded-full border border-white/10">
                                <div>
                                    <p class="text-sm font-semibold">{{ $transaction->user->name }}</p>
                                    <p class="text-xs text-white/60">{{ $transaction->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-base sm:text-lg font-bold text-primary">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <div class="flex items-center gap-2">
                                @if($transaction->payment_method === 'bank_transfer')
                                    <x-icon name="bank" class="w-4 h-4" />
                                    <span>Bank Transfer</span>
                                @elseif($transaction->payment_method === 'qris')
                                    <x-icon name="qr-code" class="w-4 h-4" />
                                    <span>QRIS</span>
                                @else
                                    <x-icon name="wallet" class="w-4 h-4" />
                                    <span>Manual</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($transaction->proof_path)
                                <a href="{{ asset('storage/' . $transaction->proof_path) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-semibold border border-blue-500/30 hover:bg-blue-500/30 transition-colors">
                                    <x-icon name="eye" class="w-3 h-3" />
                                    Lihat Bukti
                                </a>
                            @else
                                <span class="text-white/40 text-xs">Tidak ada</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($transaction->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-semibold border border-green-500/30">
                                    <x-icon name="check" class="w-3 h-3" />
                                    Completed
                                </span>
                            @elseif($transaction->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-semibold border border-blue-500/30">
                                    <x-icon name="check-circle" class="w-3 h-3" />
                                    Approved
                                </span>
                            @elseif($transaction->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-semibold border border-yellow-500/30">
                                    <x-icon name="clock" class="w-3 h-3" />
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/20 text-red-400 rounded-lg text-xs font-semibold border border-red-500/30">
                                    <x-icon name="x" class="w-3 h-3" />
                                    Rejected
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-white/60 text-xs sm:text-sm">
                            <div class="flex items-center gap-1.5">
                                <x-icon name="calendar" class="w-3 h-3" />
                                <span>{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($transaction->approved_at)
                            <div class="flex items-center gap-1.5 mt-1 text-xs text-white/40">
                                <x-icon name="check" class="w-3 h-3" />
                                <span>Approved: {{ $transaction->approved_at->format('d M Y, H:i') }}</span>
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($transaction->status === 'pending')
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            onclick="showApproveModal({{ $transaction->id }}, '{{ $transaction->reference_number }}', {{ $transaction->amount }})"
                                            class="px-3 py-1.5 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded-lg text-xs font-semibold border border-green-500/30 transition-colors flex items-center gap-1.5">
                                        <x-icon name="check" class="w-3 h-3" />
                                        Approve
                                    </button>
                                    <button type="button" 
                                            onclick="showRejectModal({{ $transaction->id }}, '{{ $transaction->reference_number }}', {{ $transaction->amount }})"
                                            class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-xs font-semibold border border-red-500/30 transition-colors flex items-center gap-1.5">
                                        <x-icon name="x" class="w-3 h-3" />
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-white/40 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @endif
        
        @else
        <!-- Empty State -->
        <div class="text-center py-12 sm:py-16 px-4">
            <div class="w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 rounded-2xl bg-primary/10 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                <x-icon name="wallet" class="w-10 h-10 sm:w-12 sm:h-12 text-primary" />
            </div>
            <h3 class="text-xl sm:text-2xl font-bold mb-2">Tidak Ada Permintaan Top-Up</h3>
            <p class="text-white/60 text-sm sm:text-base mb-6 max-w-md mx-auto">
                @if($status === 'pending')
                    Tidak ada permintaan top-up yang menunggu verifikasi saat ini.
                @else
                    Tidak ada transaksi dengan status "{{ ucfirst($status) }}".
                @endif
            </p>
            @if($status !== 'pending')
            <a href="{{ route('admin.wallet.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all hover:scale-105 font-semibold text-base">
                <x-icon name="arrow-left" class="w-5 h-5" />
                <span>Lihat Pending</span>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
     style="display: none;"
     onclick="if (event.target === this) closeApproveModal()">
    <div class="glass p-4 sm:p-6 rounded-xl max-w-md w-full shadow-2xl border border-white/10" onclick="event.stopPropagation()">
        <div class="flex items-start gap-4 mb-4">
            <div class="flex-shrink-0">
                <x-icon name="info" class="w-8 h-8 text-blue-400" />
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-2">Setujui Top-Up</h3>
                <p class="text-white/80 text-sm sm:text-base" id="approveModalMessage"></p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <button type="button"
                    onclick="closeApproveModal()"
                    class="px-4 py-2.5 glass glass-hover rounded-lg font-semibold transition-colors order-2 sm:order-1">
                Batal
            </button>
            <button type="button"
                    id="approveModalConfirmBtn"
                    class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition-colors order-1 sm:order-2">
                Ya, Setujui
            </button>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
     style="display: none;"
     onclick="if (event.target === this) closeRejectModal()">
    <div class="glass p-6 rounded-xl max-w-md w-full border border-white/10 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-start gap-4 mb-4">
            <div class="flex-shrink-0">
                <x-icon name="alert-circle" class="w-8 h-8 text-red-400" />
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold mb-2">Tolak Top-Up</h3>
                <div class="mb-4">
                    <p class="text-white/80 mb-1 text-sm">Reference: <span class="font-mono text-sm" id="modalReference"></span></p>
                    <p class="text-white/80 text-sm">Jumlah: <span class="font-bold text-primary" id="modalAmount"></span></p>
                </div>
                <form method="POST" id="rejectForm">
                    @csrf
                    <label class="block text-sm font-semibold mb-2">Alasan Penolakan *</label>
                    <textarea name="reason" 
                              rows="4" 
                              required
                              placeholder="Masukkan alasan penolakan..."
                              class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none"></textarea>
                </form>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="button" 
                    onclick="closeRejectModal()"
                    class="flex-1 px-4 py-2.5 glass glass-hover rounded-lg font-semibold border border-white/10 transition-colors">
                Batal
            </button>
            <button type="button"
                    onclick="submitRejectForm()"
                    class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition-colors">
                Tolak
            </button>
        </div>
    </div>
</div>

<script>
let approveTransactionId = null;

function showApproveModal(id, reference, amount) {
    approveTransactionId = id;
    const modal = document.getElementById('approveModal');
    const messageEl = document.getElementById('approveModalMessage');
    const confirmBtn = document.getElementById('approveModalConfirmBtn');
    
    // Update message
    messageEl.textContent = `Setujui top-up sebesar Rp ${amount.toLocaleString('id-ID')}?`;
    
    // Remove old event listener and add new one
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.onclick = function() {
        // Submit approve form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('/admin/wallet') }}/${id}/approve`;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    };
    
    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function showRejectModal(id, reference, amount) {
    document.getElementById('modalReference').textContent = reference;
    document.getElementById('modalAmount').textContent = 'Rp ' + amount.toLocaleString('id-ID');
    document.getElementById('rejectForm').action = `{{ url('/admin/wallet') }}/${id}/reject`;
    
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
    document.getElementById('rejectForm').reset();
}

function submitRejectForm() {
    const form = document.getElementById('rejectForm');
    if (form.reportValidity()) {
        form.submit();
    }
}

// Close modals on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeApproveModal();
        closeRejectModal();
    }
});
</script>
@endsection

