@extends('seller.layouts.dashboard')

@section('title', 'Wallet - Seller Dashboard - Ebrystoree')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                <x-icon name="wallet" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Wallet</h1>
                <p class="text-white/60 text-sm sm:text-base">Kelola saldo dan transaksi Anda</p>
            </div>
        </div>
        <a href="{{ route('seller.wallet.topUp') }}" 
           class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all hover:scale-105 text-sm sm:text-base text-center touch-target flex items-center justify-center gap-2 font-semibold">
            <x-icon name="arrow-up" class="w-5 h-5" />
            <span>Top Up</span>
        </a>
    </div>
    
    <!-- Balance Card - Redesigned -->
    <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 lg:p-10 bg-gradient-to-br from-primary/20 via-primary/10 to-blue-500/10 border border-primary/30 hover:border-primary/50 transition-all duration-300">
        <div class="absolute top-0 right-0 w-40 h-40 bg-primary/10 rounded-full blur-[80px] group-hover:bg-primary/20 transition-all"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full blur-[60px] group-hover:bg-blue-500/20 transition-all"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <x-icon name="wallet" class="w-7 h-7 sm:w-8 sm:h-8 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-semibold text-white/80 mb-1">Saldo Wallet</h2>
                        <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-primary break-words">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4">
                <a href="{{ route('seller.wallet.topUp') }}" 
                   class="flex-1 sm:flex-none px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all hover:scale-105 touch-target text-sm sm:text-base font-semibold flex items-center justify-center gap-2">
                    <x-icon name="arrow-up" class="w-5 h-5" />
                    <span>Top Up</span>
                </a>
                <a href="{{ route('seller.orders.index') }}" 
                   class="flex-1 sm:flex-none px-6 py-3 glass glass-hover rounded-lg touch-target text-sm sm:text-base font-semibold flex items-center justify-center gap-2 border border-white/10">
                    <x-icon name="list" class="w-5 h-5" />
                    <span>Riwayat Pesanan</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="glass p-4 sm:p-6 rounded-lg" x-data="{ type: '{{ request('type') ?? '' }}', status: '{{ request('status') ?? '' }}' }">
        <div class="flex items-center gap-2 mb-4">
            <x-icon name="filter" class="w-5 h-5 text-primary" />
            <h3 class="text-base sm:text-lg font-semibold">Filter Transaksi</h3>
        </div>
        <form method="GET" action="{{ route('seller.wallet.index') }}" class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <select name="type" 
                    x-model="type" 
                    class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-base sm:text-sm touch-target focus:outline-none focus:border-primary">
                <option value="">Semua Tipe</option>
                <option value="top_up">Top Up</option>
                <option value="deduction">Pembayaran</option>
                <option value="refund">Refund</option>
            </select>
            
            <select name="status" 
                    x-model="status" 
                    class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-base sm:text-sm touch-target focus:outline-none focus:border-primary">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="rejected">Rejected</option>
            </select>
            
            <button type="submit" 
                    class="px-4 sm:px-6 py-3 sm:py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-base sm:text-sm touch-target flex items-center justify-center gap-2 font-semibold">
                <x-icon name="filter" class="w-4 h-4" />
                <span>Filter</span>
            </button>
            
            @if(request('type') || request('status'))
            <a href="{{ route('seller.wallet.index') }}" 
               class="px-4 sm:px-6 py-3 sm:py-2 glass glass-hover rounded-lg text-center text-base sm:text-sm touch-target flex items-center justify-center gap-2 font-semibold border border-white/10">
                <x-icon name="refresh" class="w-4 h-4" />
                <span>Reset</span>
            </a>
            @endif
        </form>
    </div>
    
    <!-- Transaction History -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                    <x-icon name="list" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold">Riwayat Transaksi</h2>
                    <p class="text-white/60 text-xs sm:text-sm">{{ $transactions->total() }} transaksi</p>
                </div>
            </div>
        </div>
        
        @if($transactions->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Reference</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Tipe</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Jumlah</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm font-semibold">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4 font-mono text-xs sm:text-sm text-white/80">{{ $transaction->reference_number ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            @if($transaction->type === 'top_up')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-semibold border border-blue-500/30">
                                    <x-icon name="arrow-up" class="w-3 h-3" />
                                    Top Up
                                </span>
                            @elseif($transaction->type === 'deduction')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/20 text-red-400 rounded-lg text-xs font-semibold border border-red-500/30">
                                    <x-icon name="arrow-down" class="w-3 h-3" />
                                    Payment
                                </span>
                            @elseif($transaction->type === 'refund')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-semibold border border-green-500/30">
                                    <x-icon name="refresh" class="w-3 h-3" />
                                    Refund
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-500/20 text-purple-400 rounded-lg text-xs font-semibold border border-purple-500/30">
                                    <x-icon name="withdraw" class="w-3 h-3" />
                                    Withdrawal
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="{{ $transaction->type === 'top_up' || $transaction->type === 'refund' ? 'text-green-400' : 'text-red-400' }} font-bold text-base flex items-center gap-1">
                                @if($transaction->type === 'top_up' || $transaction->type === 'refund')
                                    <x-icon name="arrow-up" class="w-4 h-4" />
                                @else
                                    <x-icon name="arrow-down" class="w-4 h-4" />
                                @endif
                                {{ $transaction->type === 'top_up' || $transaction->type === 'refund' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($transaction->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-semibold border border-green-500/30">
                                    <x-icon name="check" class="w-3 h-3" />
                                    Completed
                                </span>
                            @elseif($transaction->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-semibold border border-yellow-500/30">
                                    <x-icon name="clock" class="w-3 h-3" />
                                    Pending
                                </span>
                            @elseif($transaction->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-semibold border border-blue-500/30">
                                    <x-icon name="check-circle" class="w-3 h-3" />
                                    Approved
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
                        </td>
                        <td class="py-3 px-4 text-white/70 text-xs sm:text-sm">{{ $transaction->description ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @foreach($transactions as $transaction)
            <div class="glass glass-hover p-4 rounded-xl border border-white/10 transition-all hover:border-primary/30">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            @if($transaction->type === 'top_up')
                                <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                                    <x-icon name="arrow-up" class="w-4 h-4 text-blue-400" />
                                </div>
                            @elseif($transaction->type === 'deduction')
                                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center border border-red-500/30">
                                    <x-icon name="arrow-down" class="w-4 h-4 text-red-400" />
                                </div>
                            @elseif($transaction->type === 'refund')
                                <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                                    <x-icon name="refresh" class="w-4 h-4 text-green-400" />
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center border border-purple-500/30">
                                    <x-icon name="withdraw" class="w-4 h-4 text-purple-400" />
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm">
                                    @if($transaction->type === 'top_up')
                                        Top Up
                                    @elseif($transaction->type === 'deduction')
                                        Payment
                                    @elseif($transaction->type === 'refund')
                                        Refund
                                    @else
                                        Withdrawal
                                    @endif
                                </p>
                                <p class="font-mono text-xs text-white/60 truncate mt-0.5">{{ $transaction->reference_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($transaction->status === 'completed')
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-semibold border border-green-500/30">
                                <x-icon name="check" class="w-3 h-3" />
                            </span>
                        @elseif($transaction->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-lg text-xs font-semibold border border-yellow-500/30">
                                <x-icon name="clock" class="w-3 h-3" />
                            </span>
                        @elseif($transaction->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-500/20 text-blue-400 rounded-lg text-xs font-semibold border border-blue-500/30">
                                <x-icon name="check-circle" class="w-3 h-3" />
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-500/20 text-red-400 rounded-lg text-xs font-semibold border border-red-500/30">
                                <x-icon name="x" class="w-3 h-3" />
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-3 pt-3 border-t border-white/10">
                    <span class="{{ $transaction->type === 'top_up' || $transaction->type === 'refund' ? 'text-green-400' : 'text-red-400' }} font-bold text-lg sm:text-xl flex items-center gap-1.5">
                        @if($transaction->type === 'top_up' || $transaction->type === 'refund')
                            <x-icon name="arrow-up" class="w-5 h-5" />
                        @else
                            <x-icon name="arrow-down" class="w-5 h-5" />
                        @endif
                        {{ $transaction->type === 'top_up' || $transaction->type === 'refund' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    </span>
                    <div class="flex items-center gap-1.5 text-xs text-white/60">
                        <x-icon name="calendar" class="w-3 h-3" />
                        <span>{{ $transaction->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                @if($transaction->description)
                <p class="text-xs text-white/70 mt-2 pt-2 border-t border-white/5">{{ $transaction->description }}</p>
                @endif
            </div>
            @endforeach
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
            <h3 class="text-xl sm:text-2xl font-bold mb-2">Belum Ada Transaksi</h3>
            <p class="text-white/60 text-sm sm:text-base mb-6 max-w-md mx-auto">
                Mulai dengan melakukan top-up untuk menambah saldo wallet Anda
            </p>
            <a href="{{ route('seller.wallet.topUp') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all hover:scale-105 font-semibold text-base">
                <x-icon name="arrow-up" class="w-5 h-5" />
                <span>Top Up Sekarang</span>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

