@php
    $escrow = $order->escrow;
    $holdPeriodDays = $escrow->hold_until ? now()->diffInDays($escrow->hold_until, false) : 0;
    $isHolding = $escrow->status === 'holding';
    $isReleased = $escrow->status === 'released';
    $isDisputed = $escrow->status === 'disputed';
    $isRefunded = $escrow->status === 'refunded';
@endphp

<div class="glass p-4 sm:p-6 rounded-xl border border-white/10">
    <div class="flex items-center gap-3 mb-4">
        <x-icon name="shield" class="w-6 h-6 text-primary" />
        <h3 class="text-lg font-semibold">Status Escrow / Rekber</h3>
    </div>

    @if($isHolding)
        <!-- Holding State -->
        <div class="space-y-4">
            <div class="p-4 rounded-lg bg-blue-500/10 border border-blue-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-blue-300 font-medium">Dana Ditahan</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        Holding
                    </span>
                </div>
                <p class="text-2xl font-bold text-blue-400 mb-1">
                    Rp {{ number_format($escrow->amount, 0, ',', '.') }}
                </p>
                <p class="text-sm text-white/70">
                    Dana akan dilepas setelah periode hold atau saat buyer konfirmasi selesai
                </p>
            </div>

            @if($escrow->hold_until)
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-white/60">Periode Hold:</span>
                    <span class="font-semibold">{{ $holdPeriodDays }} hari</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-white/60">Akan dilepas pada:</span>
                    <span class="font-semibold">{{ $escrow->hold_until->format('d M Y, H:i') }}</span>
                </div>
                @if($holdPeriodDays > 0)
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-white/60">Sisa waktu hold:</span>
                        <span class="font-semibold" x-data="{ 
                            days: {{ $holdPeriodDays }},
                            hours: {{ now()->diffInHours($escrow->hold_until, false) % 24 }},
                            minutes: {{ now()->diffInMinutes($escrow->hold_until, false) % 60 }}
                        }" x-init="setInterval(() => {
                            minutes--;
                            if (minutes < 0) { minutes = 59; hours--; }
                            if (hours < 0) { hours = 23; days--; }
                            if (days < 0) { days = 0; hours = 0; minutes = 0; }
                        }, 60000)">
                            <span x-text="days"></span> hari, 
                            <span x-text="hours"></span> jam, 
                            <span x-text="minutes"></span> menit
                        </span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2">
                        <div class="bg-primary h-2 rounded-full transition-all duration-1000" 
                             style="width: {{ min(100, max(0, (1 - ($holdPeriodDays / ($escrow->hold_until->diffInDays($escrow->created_at) ?: 1))) * 100)) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                <p class="text-xs text-white/70">
                    <strong>Info:</strong> Dana akan otomatis dilepas setelah periode hold selesai, 
                    atau lebih cepat jika buyer mengkonfirmasi pesanan selesai.
                </p>
            </div>
        </div>

    @elseif($isReleased)
        <!-- Released State -->
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/30">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-green-300 font-medium">Dana Dilepas</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                    Released
                </span>
            </div>
            <p class="text-2xl font-bold text-green-400 mb-2">
                Rp {{ number_format($escrow->seller_earning ?? $escrow->amount, 0, ',', '.') }}
            </p>
            <div class="space-y-1 text-sm text-white/70">
                <div>Dilepas pada: <strong>{{ $escrow->released_at->format('d M Y, H:i') }}</strong></div>
                @if($escrow->release_type)
                <div>Tipe: <strong>{{ ucfirst($escrow->release_type) }}</strong></div>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-white/10">
                <a href="{{ route('seller.earnings.index') }}" class="text-primary hover:underline text-sm font-medium">
                    Lihat Earning & Withdrawal →
                </a>
            </div>
        </div>

    @elseif($isDisputed)
        <!-- Disputed State -->
        <div class="p-4 rounded-lg bg-orange-500/10 border border-orange-500/30">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-orange-300 font-medium">Dispute Aktif</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30">
                    Disputed
                </span>
            </div>
            <p class="text-2xl font-bold text-orange-400 mb-3">
                Rp {{ number_format($escrow->amount, 0, ',', '.') }}
            </p>
            @if($escrow->dispute_reason)
            <div class="mb-3 p-3 rounded-lg bg-white/5 border border-white/10">
                <p class="text-xs text-white/60 mb-1">Alasan Dispute:</p>
                <p class="text-sm text-white/80">{{ Str::limit($escrow->dispute_reason, 150) }}</p>
            </div>
            @endif
            <div class="text-sm text-white/70 space-y-1">
                <div>Dispute dibuat: <strong>{{ $escrow->disputed_at->format('d M Y, H:i') }}</strong></div>
                <p class="text-xs text-orange-300 mt-2">
                    Admin sedang meninjau dispute ini. Dana akan dilepas ke Anda atau dikembalikan ke buyer setelah admin menyelesaikan.
                </p>
            </div>
        </div>

    @elseif($isRefunded)
        <!-- Refunded State -->
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/30">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-red-300 font-medium">Dana Dikembalikan</span>
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                    Refunded
                </span>
            </div>
            <p class="text-sm text-white/70">
                Dana telah dikembalikan ke buyer sesuai keputusan admin.
            </p>
        </div>
    @endif

    <!-- Info Box with Better Explanation -->
    <div class="mt-4 p-3 rounded-lg bg-blue-500/10 border border-blue-500/30">
        <div class="flex items-start gap-2 mb-2">
            <x-icon name="info" class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" />
            <h5 class="text-xs font-semibold text-blue-400">Tentang Perlindungan Pembayaran</h5>
        </div>
        <p class="text-xs text-white/80 leading-relaxed mb-2">
            <strong>Perlindungan Pembayaran (Escrow / Rekber)</strong> melindungi transaksi dengan menahan dana sementara sampai pesanan selesai.
        </p>
        <div class="text-xs text-white/70 space-y-1">
            <p>• Dana akan <strong>otomatis dilepas</strong> setelah periode hold selesai</p>
            <p>• Atau <strong>lebih cepat</strong> jika buyer mengkonfirmasi pesanan selesai</p>
            <p>• Dana akan masuk ke <strong>Seller Earning</strong> dan bisa di-withdraw</p>
        </div>
        <details class="mt-2">
            <summary class="text-xs text-blue-400 cursor-pointer hover:text-blue-300">Pelajari lebih lanjut</summary>
            <div class="mt-2 text-xs text-white/70 space-y-1 pl-4">
                <p>• <strong>Hold Period:</strong> Periode waktu dana ditahan untuk memastikan buyer puas dengan produk/jasa</p>
                <p>• <strong>Early Release:</strong> Buyer bisa melepas escrow lebih cepat dengan konfirmasi selesai</p>
                <p>• <strong>Dispute:</strong> Jika buyer membuat dispute, admin akan meninjau dan memutuskan</p>
            </div>
        </details>
    </div>
    
    <!-- Escrow Timeline -->
    <x-escrow-timeline :escrow="$escrow" :order="$order" />
</div>

