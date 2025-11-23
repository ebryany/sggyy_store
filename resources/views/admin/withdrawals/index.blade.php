@extends('layouts.app')

@section('title', 'Kelola Penarikan Saldo Seller - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">💸 Kelola Penarikan Saldo Seller</h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola dan proses permintaan penarikan saldo dari seller</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
        <div class="glass p-4 rounded-lg border border-white/10">
            <p class="text-white/60 text-xs mb-1">Total</p>
            <p class="text-xl sm:text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="glass p-4 rounded-lg border border-yellow-500/30 bg-yellow-500/5">
            <p class="text-yellow-400 text-xs mb-1">Pending</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-400">{{ $stats['pending'] }}</p>
            <p class="text-xs text-white/60 mt-1">Rp {{ number_format($stats['total_amount_pending'], 0, ',', '.') }}</p>
        </div>
        <div class="glass p-4 rounded-lg border border-blue-500/30 bg-blue-500/5">
            <p class="text-blue-400 text-xs mb-1">Processing</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-400">{{ $stats['processing'] }}</p>
            <p class="text-xs text-white/60 mt-1">Rp {{ number_format($stats['total_amount_processing'], 0, ',', '.') }}</p>
        </div>
        <div class="glass p-4 rounded-lg border border-green-500/30 bg-green-500/5">
            <p class="text-green-400 text-xs mb-1">Completed</p>
            <p class="text-xl sm:text-2xl font-bold text-green-400">{{ $stats['completed'] }}</p>
        </div>
        <div class="glass p-4 rounded-lg border border-red-500/30 bg-red-500/5">
            <p class="text-red-400 text-xs mb-1">Rejected</p>
            <p class="text-xl sm:text-2xl font-bold text-red-400">{{ $stats['rejected'] }}</p>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="flex flex-wrap items-center gap-3 sm:gap-4">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari seller atau reference number..."
                   class="flex-1 min-w-[200px] glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
            <select name="status" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <select name="method" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <option value="">Semua Metode</option>
                <option value="bank_transfer" {{ request('method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="e_wallet" {{ request('method') === 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                🔍 Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'method']))
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="px-6 py-2 glass glass-hover rounded-lg transition-colors font-semibold">
                Reset
            </a>
            @endif
        </form>
    </div>
    
    <!-- Withdrawals Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <h2 class="text-xl sm:text-2xl font-semibold mb-4">Daftar Penarikan Saldo</h2>
        
        @if($withdrawals->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Reference</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Seller</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Jumlah</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Metode</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Rekening</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $withdrawal)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4">
                            <p class="font-semibold text-sm">{{ $withdrawal->reference_number }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <img src="{{ $withdrawal->seller->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($withdrawal->seller->name) }}" 
                                     alt="{{ $withdrawal->seller->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-semibold text-sm">{{ $withdrawal->seller->name }}</p>
                                    <p class="text-xs text-white/60">{{ $withdrawal->seller->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <p class="font-bold text-primary text-base">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                {{ $withdrawal->method === 'bank_transfer' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400' }}">
                                {{ $withdrawal->method === 'bank_transfer' ? '🏦 Bank' : '📱 E-Wallet' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm">{{ $withdrawal->bank_name ?? '-' }}</p>
                            <p class="text-xs text-white/60">{{ $withdrawal->account_number ?? '-' }}</p>
                            @if($withdrawal->account_name)
                            <p class="text-xs text-white/60">a.n. {{ $withdrawal->account_name }}</p>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                @if($withdrawal->status === 'completed') bg-green-500/20 text-green-400 border border-green-500/30
                                @elseif($withdrawal->status === 'processing') bg-blue-500/20 text-blue-400 border border-blue-500/30
                                @elseif($withdrawal->status === 'rejected') bg-red-500/20 text-red-400 border border-red-500/30
                                @else bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                                @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <p class="text-sm">{{ $withdrawal->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-white/60">{{ $withdrawal->created_at->format('H:i') }}</p>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" 
                                   class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded text-xs hover:bg-blue-500/30 whitespace-nowrap transition-colors font-semibold">
                                    👁️ Detail
                                </a>
                                @if($withdrawal->status === 'pending')
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('approve-withdrawal-modal-{{ $withdrawal->id }}');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        "
                                        class="px-3 py-1 bg-green-500/20 text-green-400 rounded text-xs hover:bg-green-500/30 whitespace-nowrap transition-colors font-semibold">
                                    ✅ Approve
                                </button>
                                
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('complete-withdrawal-modal-{{ $withdrawal->id }}');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        "
                                        class="px-3 py-1 bg-primary/20 text-primary rounded text-xs hover:bg-primary/30 whitespace-nowrap transition-colors font-semibold">
                                    ✓ Complete
                                </button>
                                
                                <!-- Approve Modal -->
                                <x-confirm-modal 
                                    id="approve-withdrawal-modal-{{ $withdrawal->id }}"
                                    title="Setujui Penarikan Saldo"
                                    message="Setujui penarikan saldo ini?"
                                    confirm-text="Ya, Setujui"
                                    cancel-text="Batal"
                                    type="info" />
                                
                                <form id="approve-withdrawal-form-{{ $withdrawal->id }}" method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}" style="display: none;">
                                    @csrf
                                </form>
                                
                                <!-- Complete Modal -->
                                <x-confirm-modal 
                                    id="complete-withdrawal-modal-{{ $withdrawal->id }}"
                                    title="Tandai Sebagai Selesai"
                                    message="Tandai sebagai selesai? Pastikan transfer sudah dilakukan."
                                    confirm-text="Ya, Tandai Selesai"
                                    cancel-text="Batal"
                                    type="info" />
                                
                                <form id="complete-withdrawal-form-{{ $withdrawal->id }}" method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}" style="display: none;">
                                    @csrf
                                </form>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Approve
                                        const approveBtn{{ $withdrawal->id }} = document.getElementById('approve-withdrawal-modal-{{ $withdrawal->id }}-confirm-btn');
                                        const approveModal{{ $withdrawal->id }} = document.getElementById('approve-withdrawal-modal-{{ $withdrawal->id }}');
                                        const approveForm{{ $withdrawal->id }} = document.getElementById('approve-withdrawal-form-{{ $withdrawal->id }}');
                                        
                                        if (approveBtn{{ $withdrawal->id }} && approveModal{{ $withdrawal->id }} && approveForm{{ $withdrawal->id }}) {
                                            approveBtn{{ $withdrawal->id }}.addEventListener('click', function() {
                                                approveModal{{ $withdrawal->id }}.style.display = 'none';
                                                document.body.style.overflow = '';
                                                approveForm{{ $withdrawal->id }}.submit();
                                            });
                                        }
                                        
                                        // Complete
                                        const completeBtn{{ $withdrawal->id }} = document.getElementById('complete-withdrawal-modal-{{ $withdrawal->id }}-confirm-btn');
                                        const completeModal{{ $withdrawal->id }} = document.getElementById('complete-withdrawal-modal-{{ $withdrawal->id }}');
                                        const completeForm{{ $withdrawal->id }} = document.getElementById('complete-withdrawal-form-{{ $withdrawal->id }}');
                                        
                                        if (completeBtn{{ $withdrawal->id }} && completeModal{{ $withdrawal->id }} && completeForm{{ $withdrawal->id }}) {
                                            completeBtn{{ $withdrawal->id }}.addEventListener('click', function() {
                                                completeModal{{ $withdrawal->id }}.style.display = 'none';
                                                document.body.style.overflow = '';
                                                completeForm{{ $withdrawal->id }}.submit();
                                            });
                                        }
                                    });
                                </script>
                                
                                @elseif($withdrawal->status === 'processing')
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('complete-processing-modal-{{ $withdrawal->id }}');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        "
                                        class="px-3 py-1 bg-primary/20 text-primary rounded text-xs hover:bg-primary/30 whitespace-nowrap transition-colors font-semibold">
                                    ✓ Complete
                                </button>
                                
                                <!-- Complete Processing Modal -->
                                <x-confirm-modal 
                                    id="complete-processing-modal-{{ $withdrawal->id }}"
                                    title="Tandai Sebagai Selesai"
                                    message="Tandai sebagai selesai? Pastikan transfer sudah dilakukan."
                                    confirm-text="Ya, Tandai Selesai"
                                    cancel-text="Batal"
                                    type="info" />
                                
                                <form id="complete-processing-form-{{ $withdrawal->id }}" method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}" style="display: none;">
                                    @csrf
                                </form>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const completeBtn{{ $withdrawal->id }} = document.getElementById('complete-processing-modal-{{ $withdrawal->id }}-confirm-btn');
                                        const completeModal{{ $withdrawal->id }} = document.getElementById('complete-processing-modal-{{ $withdrawal->id }}');
                                        const completeForm{{ $withdrawal->id }} = document.getElementById('complete-processing-form-{{ $withdrawal->id }}');
                                        
                                        if (completeBtn{{ $withdrawal->id }} && completeModal{{ $withdrawal->id }} && completeForm{{ $withdrawal->id }}) {
                                            completeBtn{{ $withdrawal->id }}.addEventListener('click', function() {
                                                completeModal{{ $withdrawal->id }}.style.display = 'none';
                                                document.body.style.overflow = '';
                                                completeForm{{ $withdrawal->id }}.submit();
                                            });
                                        }
                                    });
                                </script>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $withdrawals->links() }}
        </div>
        @else
        <div class="text-center py-12 px-4">
            <span class="text-5xl mb-3 block">💸</span>
            <p class="text-white/40 text-sm">Belum ada permintaan penarikan saldo</p>
        </div>
        @endif
    </div>
</div>
@endsection







