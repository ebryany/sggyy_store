@props(['escrow', 'order'])

@php
    $isHolding = $escrow->isHolding();
    $isReleased = $escrow->isReleased();
    $isDisputed = $escrow->isDisputed();
    $isRefunded = $escrow->isRefunded();
    
    $holdUntil = $escrow->hold_until;
    $daysRemaining = $holdUntil ? now()->diffInDays($holdUntil, false) : 0;
    $hoursRemaining = $holdUntil ? now()->diffInHours($holdUntil, false) : 0;
    $progress = $holdUntil ? max(0, min(100, (1 - ($holdUntil->diffInSeconds(now()) / $holdUntil->diffInSeconds($escrow->created_at))) * 100)) : 0;
@endphp

<div class="glass p-5 sm:p-6 rounded-xl border border-white/10">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center
                {{ $isHolding ? 'bg-blue-500/20 border border-blue-500/30' : '' }}
                {{ $isReleased ? 'bg-green-500/20 border border-green-500/30' : '' }}
                {{ $isDisputed ? 'bg-orange-500/20 border border-orange-500/30' : '' }}
                {{ $isRefunded ? 'bg-red-500/20 border border-red-500/30' : '' }}">
                @if($isHolding)
                    <x-icon name="shield" class="w-6 h-6 text-blue-400" />
                @elseif($isReleased)
                    <x-icon name="check" class="w-6 h-6 text-green-400" />
                @elseif($isDisputed)
                    <x-icon name="alert" class="w-6 h-6 text-orange-400" />
                @elseif($isRefunded)
                    <x-icon name="x" class="w-6 h-6 text-red-400" />
                @endif
            </div>
            <div>
                <h3 class="font-semibold text-white mb-1">Status Escrow / Rekber</h3>
                <div class="flex items-center gap-2">
                    @if($isHolding)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            Dana Ditahan
                        </span>
                    @elseif($isReleased)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                            Dana Dilepas
                        </span>
                    @elseif($isDisputed)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30">
                            Dispute
                        </span>
                    @elseif($isRefunded)
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                            Dikembalikan
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($isHolding)
        <!-- Hold Period Info -->
        <div class="mb-4 p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-white/70">Periode Hold</span>
                <span class="text-sm font-semibold text-blue-400" 
                      x-data="countdownTimer('{{ $holdUntil->toISOString() }}')"
                      x-text="timeRemaining">
                    {{ $holdUntil->diffForHumans() }}
                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-white/10 rounded-full h-2 mb-2">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ $progress }}%"></div>
            </div>
            
            <p class="text-xs text-white/60">
                @if($daysRemaining > 0)
                    Tersisa {{ $daysRemaining }} hari
                @elseif($hoursRemaining > 0)
                    Tersisa {{ $hoursRemaining }} jam
                @else
                    Akan dilepas segera
                @endif
            </p>
        </div>

        <!-- Escrow Amount Info -->
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-white/70">Total Escrow:</span>
                <span class="font-semibold text-white">Rp {{ number_format($escrow->amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-white/70">Komisi Platform:</span>
                <span class="text-white/60">Rp {{ number_format($escrow->platform_fee, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-white/10">
                <span class="text-white/70">Earning Seller:</span>
                <span class="font-semibold text-primary">Rp {{ number_format($escrow->seller_earning, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="p-3 rounded-lg bg-white/5 border border-white/10">
            <p class="text-xs text-white/70 leading-relaxed">
                <x-icon name="info" class="w-4 h-4 inline text-blue-400" />
                Dana ditahan di escrow untuk keamanan transaksi. Dana akan dilepas setelah periode hold selesai atau saat Anda konfirmasi selesai.
            </p>
        </div>

        <!-- Early Release Button (for buyer, if order completed) -->
        @if($order->status === 'completed' && auth()->id() === $order->user_id)
        <div class="mt-4 pt-4 border-t border-white/10">
            <form action="{{ route('orders.confirmCompletion', $order) }}" method="POST" 
                  x-data="{ submitting: false }"
                  @submit="submitting = true">
                @csrf
                <button type="submit" 
                        :disabled="submitting"
                        class="w-full px-4 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-primary/20 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-icon name="check" class="w-5 h-5" />
                    <span x-show="!submitting">Konfirmasi Selesai & Lepas Escrow</span>
                    <span x-show="submitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
                <p class="text-xs text-white/60 mt-2 text-center">
                    Konfirmasi selesai akan melepas escrow segera ke seller
                </p>
            </form>
        </div>
        @endif

    @elseif($isReleased)
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/20">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="check" class="w-5 h-5 text-green-400" />
                <span class="font-semibold text-green-400">Escrow Telah Dilepas</span>
            </div>
            <p class="text-sm text-white/70 mb-2">
                @if($escrow->release_type === 'early')
                    Dilepas lebih awal saat Anda konfirmasi selesai
                @elseif($escrow->release_type === 'auto')
                    Dilepas otomatis setelah periode hold selesai
                @else
                    Dilepas secara manual oleh admin
                @endif
            </p>
            <p class="text-xs text-white/60">
                Pada: {{ $escrow->released_at->format('d M Y, H:i') }}
            </p>
        </div>

    @elseif($isDisputed)
        <div class="p-4 rounded-lg bg-orange-500/10 border border-orange-500/20">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="alert" class="w-5 h-5 text-orange-400" />
                <span class="font-semibold text-orange-400">Escrow Sedang Dispute</span>
            </div>
            <p class="text-sm text-white/70 mb-2">
                {{ $escrow->dispute_reason ?? 'Escrow sedang dalam proses dispute' }}
            </p>
            <p class="text-xs text-white/60">
                Dispute pada: {{ $escrow->disputed_at->format('d M Y, H:i') }}
            </p>
            <p class="text-xs text-white/60 mt-2">
                Admin akan meninjau dan menyelesaikan dispute ini.
            </p>
        </div>

    @elseif($isRefunded)
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="x" class="w-5 h-5 text-red-400" />
                <span class="font-semibold text-red-400">Escrow Dikembalikan</span>
            </div>
            <p class="text-sm text-white/70">
                Dana telah dikembalikan ke wallet Anda.
            </p>
        </div>
    @endif
</div>

<script>
function countdownTimer(targetDate) {
    return {
        timeRemaining: '',
        init() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = new Date();
            const target = new Date(targetDate);
            const diff = target - now;
            
            if (diff <= 0) {
                this.timeRemaining = 'Selesai';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            if (days > 0) {
                this.timeRemaining = `${days} hari ${hours} jam`;
            } else if (hours > 0) {
                this.timeRemaining = `${hours} jam ${minutes} menit`;
            } else {
                this.timeRemaining = `${minutes} menit`;
            }
        }
    };
}
</script>

