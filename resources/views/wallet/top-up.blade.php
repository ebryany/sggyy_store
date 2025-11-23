@extends('layouts.app')

@section('title', 'Top Up Wallet - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-2xl">
    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('wallet.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary/80 transition-colors touch-target text-sm sm:text-base">
            <x-icon name="arrow-left" class="w-4 h-4 sm:w-5 sm:h-5" />
            <span>Kembali ke Wallet</span>
        </a>
    </div>
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6 sm:mb-8">
        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
            <x-icon name="wallet" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Top Up Wallet</h1>
            <p class="text-white/60 text-sm sm:text-base">Tambah saldo untuk transaksi Anda</p>
        </div>
    </div>
    
    <!-- Balance Card - Redesigned -->
    <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 mb-6 sm:mb-8 bg-gradient-to-br from-primary/20 via-primary/10 to-blue-500/10 border border-primary/30 hover:border-primary/50 transition-all duration-300">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] group-hover:bg-primary/20 transition-all"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <x-icon name="wallet" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-semibold text-white/80">Saldo Saat Ini</h3>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary break-words mt-1">
                            Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <form method="POST" action="{{ route('wallet.topUp.store') }}" enctype="multipart/form-data"
          x-data="{ 
              amount: 0, 
              paymentMethod: '{{ (isset($featureFlags) && $featureFlags['enable_bank_transfer']) ? 'bank_transfer' : ((isset($featureFlags) && $featureFlags['enable_qris']) ? 'qris' : 'manual') }}',
              proofPreview: null,
              needsApproval: false,
              setAmount(value) {
                  this.amount = value;
                  this.needsApproval = value >= 1000000;
              }
          }">
        @csrf
        
        <div class="glass p-4 sm:p-6 rounded-lg space-y-6 sm:space-y-8">
            <!-- Amount Section -->
            <div>
                <label class="block text-sm sm:text-base font-semibold mb-3 flex items-center gap-2">
                    <x-icon name="dollar" class="w-5 h-5 text-primary" />
                    <span>Jumlah Top Up (Rp) *</span>
                </label>
                @php
                    $minTopup = $limits['min_topup_amount'] ?? 10000;
                    $maxTopup = $limits['max_topup_amount'] ?? 10000000;
                @endphp
                <input type="number" 
                       name="amount" 
                       x-model.number="amount"
                       @input="needsApproval = amount >= 1000000"
                       min="{{ $minTopup }}"
                       inputmode="numeric"
                       pattern="[0-9]*" 
                       max="{{ $maxTopup }}" 
                       step="1000"
                       required
                       placeholder="Masukkan jumlah top up"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-4 bg-white/5 focus:outline-none focus:border-primary text-lg sm:text-xl font-semibold touch-target">
                @error('amount')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
                
                <!-- Quick Amount Buttons -->
                <div class="mt-4">
                    <p class="text-xs sm:text-sm text-white/60 mb-2">Pilih Jumlah Cepat:</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" 
                                @click="setAmount(50000)"
                                class="px-4 py-2 glass hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium">
                            Rp 50.000
                        </button>
                        <button type="button" 
                                @click="setAmount(100000)"
                                class="px-4 py-2 glass hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium">
                            Rp 100.000
                        </button>
                        <button type="button" 
                                @click="setAmount(250000)"
                                class="px-4 py-2 glass hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium">
                            Rp 250.000
                        </button>
                        <button type="button" 
                                @click="setAmount(500000)"
                                class="px-4 py-2 glass hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium">
                            Rp 500.000
                        </button>
                        <button type="button" 
                                @click="setAmount(1000000)"
                                class="px-4 py-2 glass hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium">
                            Rp 1.000.000
                        </button>
                    </div>
                </div>
                
                <div class="mt-3 text-xs sm:text-sm text-white/60 space-y-1">
                    <p>Minimum: Rp {{ number_format($minTopup, 0, ',', '.') }}</p>
                    <p>Maximum: Rp {{ number_format($maxTopup, 0, ',', '.') }}</p>
                    <div x-show="needsApproval" 
                         x-transition
                         class="mt-3 flex items-center gap-2 p-3 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                        <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                        <p class="text-yellow-400 text-sm">
                            Top-up di atas Rp 1.000.000 memerlukan verifikasi admin
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Method Section - Card Selection -->
            <div>
                <label class="block text-sm sm:text-base font-semibold mb-3 flex items-center gap-2">
                    <x-icon name="credit-card" class="w-5 h-5 text-primary" />
                    <span>Metode Pembayaran *</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if(isset($featureFlags) && $featureFlags['enable_bank_transfer'])
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="payment_method" 
                               value="bank_transfer"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="glass p-4 rounded-xl border-2 border-white/10 peer-checked:border-blue-500/50 peer-checked:bg-blue-500/10 transition-all hover:border-blue-500/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                                    <x-icon name="bank" class="w-5 h-5 text-blue-400" />
                                </div>
                                <div>
                                    <p class="font-semibold text-sm sm:text-base">Transfer Bank</p>
                                    <p class="text-xs text-white/60">Upload bukti transfer</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    
                    @if(isset($featureFlags) && $featureFlags['enable_qris'])
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="payment_method" 
                               value="qris"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="glass p-4 rounded-xl border-2 border-white/10 peer-checked:border-green-500/50 peer-checked:bg-green-500/10 transition-all hover:border-green-500/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                                    <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                                </div>
                                <div>
                                    <p class="font-semibold text-sm sm:text-base">QRIS</p>
                                    <p class="text-xs text-white/60">Scan QR code</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                    <label class="cursor-pointer">
                        <input type="radio" 
                               name="payment_method" 
                               value="manual"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="glass p-4 rounded-xl border-2 border-white/10 peer-checked:border-primary/50 peer-checked:bg-primary/10 transition-all hover:border-primary/30">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center border border-primary/30">
                                    <x-icon name="settings" class="w-5 h-5 text-primary" />
                                </div>
                                <div>
                                    <p class="font-semibold text-sm sm:text-base">Manual (Admin)</p>
                                    <p class="text-xs text-white/60">Verifikasi manual</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                </div>
                @error('payment_method')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
            </div>
            
            <!-- Bank Transfer Info -->
            <div x-show="paymentMethod === 'bank_transfer'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="glass p-4 sm:p-6 rounded-xl border-2 border-blue-500/30 bg-blue-500/5">
                @if(isset($bankAccountInfo) && ($bankAccountInfo['bank_name'] || $bankAccountInfo['bank_account_number']))
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <x-icon name="bank" class="w-5 h-5 text-blue-400" />
                    </div>
                    <h3 class="font-semibold text-blue-400 text-base sm:text-lg">Transfer ke Rekening Berikut:</h3>
                </div>
                <div class="space-y-3 text-sm sm:text-base">
                    @if($bankAccountInfo['bank_name'])
                    <div class="flex justify-between items-center p-3 glass rounded-lg">
                        <span class="text-white/60 flex items-center gap-2">
                            <x-icon name="building" class="w-4 h-4" />
                            Bank:
                        </span>
                        <span class="font-semibold">{{ $bankAccountInfo['bank_name'] }}</span>
                    </div>
                    @endif
                    @if($bankAccountInfo['bank_account_number'])
                    <div class="flex justify-between items-center p-3 glass rounded-lg">
                        <span class="text-white/60 flex items-center gap-2">
                            <x-icon name="credit-card" class="w-4 h-4" />
                            No. Rekening:
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold font-mono">{{ $bankAccountInfo['bank_account_number'] }}</span>
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $bankAccountInfo['bank_account_number'] }}'); window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Nomor rekening disalin!', type: 'success'}}));"
                                    class="p-1.5 glass hover:bg-white/10 rounded-lg transition-colors"
                                    title="Salin nomor rekening">
                                <x-icon name="copy" class="w-4 h-4 text-primary" />
                            </button>
                        </div>
                    </div>
                    @endif
                    @if($bankAccountInfo['bank_account_name'])
                    <div class="flex justify-between items-center p-3 glass rounded-lg">
                        <span class="text-white/60 flex items-center gap-2">
                            <x-icon name="user" class="w-4 h-4" />
                            Atas Nama:
                        </span>
                        <span class="font-semibold">{{ $bankAccountInfo['bank_account_name'] }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-3 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                    <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                    <p class="text-yellow-400 text-sm">Informasi rekening bank belum dikonfigurasi. Silakan hubungi admin.</p>
                </div>
                @endif
            </div>
            
            <!-- QRIS Info -->
            <div x-show="paymentMethod === 'qris'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="glass p-4 sm:p-6 rounded-xl border-2 border-green-500/30 bg-green-500/5">
                @if(isset($bankAccountInfo) && $bankAccountInfo['qris_code'])
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                        <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                    </div>
                    <h3 class="font-semibold text-green-400 text-base sm:text-lg">Scan QRIS Berikut:</h3>
                </div>
                <div class="flex justify-center">
                    @if(filter_var($bankAccountInfo['qris_code'], FILTER_VALIDATE_URL))
                    <img src="{{ $bankAccountInfo['qris_code'] }}" 
                         alt="QRIS Code" 
                         class="w-full max-w-xs rounded-xl border-2 border-white/10 p-4 bg-white">
                    @elseif(str_starts_with($bankAccountInfo['qris_code'], 'data:image'))
                    <img src="{{ $bankAccountInfo['qris_code'] }}" 
                         alt="QRIS Code" 
                         class="w-full max-w-xs rounded-xl border-2 border-white/10 p-4 bg-white">
                    @else
                    <div class="bg-white p-4 rounded-xl border-2 border-white/10">
                        <div id="qris-code-topup" class="w-full max-w-xs"></div>
                    </div>
                    <script>
                        document.getElementById('qris-code-topup').innerHTML = '<img src="data:image/png;base64,{{ $bankAccountInfo['qris_code'] }}" alt="QRIS" class="w-full">';
                    </script>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-3 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                    <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                    <p class="text-yellow-400 text-sm">QRIS code belum dikonfigurasi. Silakan hubungi admin.</p>
                </div>
                @endif
            </div>
            
            <!-- Proof Upload (conditional) -->
            <div x-show="paymentMethod === 'bank_transfer'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0">
                <label class="block text-sm sm:text-base font-semibold mb-3 flex items-center gap-2">
                    <x-icon name="image" class="w-5 h-5 text-primary" />
                    <span>Bukti Pembayaran *</span>
                </label>
                <input type="file" 
                       name="proof_path" 
                       accept="image/jpeg,image/png,image/jpg,application/pdf"
                       @change="proofPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                @error('proof_path')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
                <p class="text-white/60 text-xs mt-2">Format: JPEG, PNG, JPG, PDF (Max 2MB)</p>
                
                <div x-show="proofPreview" 
                     x-transition
                     class="mt-4">
                    <p class="text-sm mb-2 font-medium">Preview:</p>
                    <img :src="proofPreview" 
                         alt="Proof Preview" 
                         class="w-full max-w-md rounded-lg border border-white/10" 
                         x-show="proofPreview && !proofPreview.includes('pdf')">
                    <div x-show="proofPreview && proofPreview.includes('pdf')" 
                         class="flex items-center gap-2 p-4 glass rounded-lg border border-white/10">
                        <x-icon name="file-text" class="w-8 h-8 text-primary" />
                        <span class="text-primary font-semibold">PDF File Selected</span>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm sm:text-base font-semibold mb-3 flex items-center gap-2">
                    <x-icon name="file-text" class="w-5 h-5 text-primary" />
                    <span>Catatan (Opsional)</span>
                </label>
                <textarea name="description" 
                          rows="3"
                          placeholder="Tambahkan catatan untuk top-up ini..."
                          class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
            </div>
            
            <!-- Info Box - Redesigned -->
            <div class="glass bg-blue-500/10 border-2 border-blue-500/30 p-4 sm:p-6 rounded-xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <x-icon name="info" class="w-5 h-5 text-blue-400" />
                    </div>
                    <h4 class="font-semibold text-blue-400 text-base sm:text-lg">Informasi Penting</h4>
                </div>
                <ul class="text-xs sm:text-sm text-white/80 space-y-2">
                    <li class="flex items-start gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di bawah Rp 1.000.000 akan langsung ditambahkan ke wallet</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-yellow-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di atas Rp 1.000.000 memerlukan verifikasi admin (1-2 hari kerja)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-icon name="image" class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" />
                        <span>Pastikan bukti pembayaran jelas dan valid</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-icon name="wallet" class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" />
                        <span>Saldo akan ditambahkan setelah pembayaran diverifikasi</span>
                    </li>
                </ul>
            </div>
            
            <!-- Submit -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-white/10">
                <button type="submit" 
                        class="flex-1 px-4 sm:px-6 py-3 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold touch-target text-base flex items-center justify-center gap-2">
                    <x-icon name="check" class="w-5 h-5" />
                    <span>Kirim Permintaan Top Up</span>
                </button>
                <a href="{{ route('wallet.index') }}" 
                   class="px-4 sm:px-6 py-3 sm:py-4 glass glass-hover rounded-lg text-center touch-target text-base flex items-center justify-center gap-2">
                    <x-icon name="x" class="w-5 h-5" />
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
