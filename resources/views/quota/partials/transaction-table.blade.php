@if($transactions->count() > 0)
<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b border-white/10">
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">TrxID</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Waktu</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Kode</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Tujuan</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Harga</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">saldoAwal</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Status</th>
                <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                <td class="py-3 px-4 text-xs sm:text-sm font-mono text-white/80">
                    {{ $transaction->trx_id ?? '-' }}
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/80">
                    {{ $transaction->created_at->format('d M Y H:i') }}
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-mono">
                    {{ $transaction->produk }}
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-mono">
                    {{ $transaction->tujuan }}
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-semibold">
                    Rp {{ number_format($transaction->harga, 0, ',', '.') }}
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/80">
                    Rp {{ number_format($transaction->saldo_awal, 0, ',', '.') }}
                </td>
                <td class="py-3 px-4">
                    @php
                        $statusColor = match($transaction->status) {
                            'success' => 'bg-green-500/20 text-green-400 border-green-500/50',
                            'failed' => 'bg-red-500/20 text-red-400 border-red-500/50',
                            'processing' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                            default => 'bg-gray-500/20 text-gray-400 border-gray-500/50',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold border {{ $statusColor }}">
                        {{ $transaction->status_display }}
                    </span>
                </td>
                <td class="py-3 px-4 text-xs sm:text-sm text-white/60 max-w-xs truncate" title="{{ $transaction->keterangan }}">
                    {{ $transaction->keterangan ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-12">
    <x-icon name="package" class="w-16 h-16 mx-auto mb-4 text-white/20" />
    <p class="text-white/60 text-lg mb-2">Belum ada data.</p>
    <p class="text-white/40 text-sm">Transaksi Anda akan muncul di sini</p>
</div>
@endif

