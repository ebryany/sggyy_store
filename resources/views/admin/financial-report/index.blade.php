@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 lg:py-8">
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Laporan Keuangan</h1>
                <p class="text-white/60 text-sm sm:text-base mt-1">Ringkasan penghasilan dan biaya platform</p>
            </div>
            
            <!-- Period Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.financial-report.index', ['period' => 'daily']) }}" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base {{ $period === 'daily' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                    Harian
                </a>
                <a href="{{ route('admin.financial-report.index', ['period' => 'monthly']) }}" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base {{ $period === 'monthly' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                    Bulanan
                </a>
                <a href="{{ route('admin.financial-report.index', ['period' => 'yearly']) }}" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base {{ $period === 'yearly' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10' }}">
                    Tahunan
                </a>
            </div>
        </div>

        <!-- Configuration Info -->
        <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
            <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Konfigurasi Biaya</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Fee Platform</p>
                    <p class="text-white font-semibold text-sm sm:text-base">Rp {{ number_format($configuration['platform_fee_fixed'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya QRIS</p>
                    <p class="text-white font-semibold text-sm sm:text-base">{{ $configuration['qris_fee_percent'] }}%</p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya Payout (Fixed)</p>
                    <p class="text-white font-semibold text-sm sm:text-base">Rp {{ number_format($configuration['payout_fee_fixed'], 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya Payout (Percent)</p>
                    <p class="text-white font-semibold text-sm sm:text-base">{{ $configuration['payout_fee_percent'] }}%</p>
                </div>
            </div>
        </div>

        @if($report)
            <!-- Transactions Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">📊 Transaksi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Transaksi</p>
                        <p class="text-white text-lg sm:text-xl font-bold">{{ number_format($report['transactions']['count'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Nilai Transaksi</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp {{ number_format($report['transactions']['total_amount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Biaya QRIS (5%)</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['transactions']['qris_fee'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Fee Platform</p>
                        <p class="text-green-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['transactions']['platform_fee'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Seller Earning</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp {{ number_format($report['transactions']['seller_earning'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Dana Masuk Platform</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp {{ number_format($report['transactions']['platform_revenue'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Withdrawals Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">💸 Payout Seller</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Payout</p>
                        <p class="text-white text-lg sm:text-xl font-bold">{{ number_format($report['withdrawals']['count'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Jumlah Payout</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp {{ number_format($report['withdrawals']['total_amount'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payout</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['withdrawals']['payout_fee'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Dana Diterima Seller</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp {{ number_format($report['withdrawals']['seller_received'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">📈 Ringkasan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Penghasilan Platform (Net)</p>
                        <p class="text-green-400 text-xl sm:text-2xl font-bold">Rp {{ number_format($report['summary']['platform_net_income'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payment Gateway</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['summary']['total_payment_gateway_fee'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payout</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['summary']['total_payout_fee'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Operasional</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp {{ number_format($report['summary']['total_operational_cost'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Breakdown (if available) -->
            @if(isset($report['daily_breakdown']) || isset($report['monthly_breakdown']))
                <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                    <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">
                        @if(isset($report['daily_breakdown']))
                            Breakdown Harian
                        @elseif(isset($report['monthly_breakdown']))
                            Breakdown Bulanan
                        @endif
                    </h2>
                    <div class="overflow-x-auto -mx-3 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-white/10">
                                        <th class="text-left py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Periode</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Transaksi</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Total Nilai</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Fee Platform</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Biaya QRIS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($report['daily_breakdown']))
                                        @foreach($report['daily_breakdown'] as $date => $daily)
                                            <tr class="border-b border-white/5 hover:bg-white/5">
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white whitespace-nowrap">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">{{ number_format($daily['transactions']['count'], 0, ',', '.') }}</td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">Rp {{ number_format($daily['transactions']['total_amount'], 0, ',', '.') }}</td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-green-400 text-right whitespace-nowrap">Rp {{ number_format($daily['transactions']['platform_fee'], 0, ',', '.') }}</td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-red-400 text-right whitespace-nowrap">Rp {{ number_format($daily['transactions']['qris_fee'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @elseif(isset($report['monthly_breakdown']))
                                        @foreach($report['monthly_breakdown'] as $month => $monthly)
                                            @if($monthly['transactions']['count'] > 0)
                                                <tr class="border-b border-white/5 hover:bg-white/5">
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white whitespace-nowrap">{{ \Carbon\Carbon::create($monthly['period']['year'], $monthly['period']['month'], 1)->format('M Y') }}</td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">{{ number_format($monthly['transactions']['count'], 0, ',', '.') }}</td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">Rp {{ number_format($monthly['transactions']['total_amount'], 0, ',', '.') }}</td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-green-400 text-right whitespace-nowrap">Rp {{ number_format($monthly['transactions']['platform_fee'], 0, ',', '.') }}</td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-red-400 text-right whitespace-nowrap">Rp {{ number_format($monthly['transactions']['qris_fee'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="glass p-6 sm:p-8 rounded-xl border border-white/5 text-center">
                <p class="text-white/60 text-sm sm:text-base">Tidak ada data untuk periode yang dipilih</p>
            </div>
        @endif
    </div>
</div>
@endsection

