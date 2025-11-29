@extends('layouts.user')

@section('title', 'Top Up Wallet - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 lg:py-8 max-w-4xl">
    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('wallet.index') }}" 
           class="inline-flex items-center gap-2 text-white/70 hover:text-primary transition-colors touch-target text-sm sm:text-base group">
            <x-icon name="arrow-left" class="w-4 h-4 sm:w-5 sm:h-5 group-hover:-translate-x-1 transition-transform" />
            <span>Kembali ke Wallet</span>
        </a>
    </div>
    
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 mb-2">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-primary/10 flex items-center justify-center border border-primary/20">
                <x-icon name="wallet" class="w-6 h-6 sm:w-7 sm:h-7 text-primary" />
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">Top Up Wallet</h1>
                <p class="text-white/60 text-sm sm:text-base mt-1">Tambah saldo untuk transaksi Anda</p>
            </div>
        </div>
    </div>
    
    <!-- Balance Card -->
    <div class="relative overflow-hidden rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 bg-gradient-to-br from-primary/10 via-primary/5 to-blue-500/5 border border-primary/20">
        <div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <p class="text-xs sm:text-sm text-white/60 mb-1">Saldo Saat Ini</p>
            <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary">
                Rp {{ number_format(auth()->user()->wallet_balance, 0, ',', '.') }}
            </p>
        </div>
    </div>
    
    <form method="POST" action="{{ route('wallet.topUp.store') }}" enctype="multipart/form-data"
          x-data="{ 
              amount: {{ old('amount', 0) }}, 
              paymentMethod: '{{ old('payment_method', (isset($featureFlags) && $featureFlags['enable_veripay']) ? 'veripay_qris' : ((isset($featureFlags) && $featureFlags['enable_bank_transfer']) ? 'bank_transfer' : ((isset($featureFlags) && $featureFlags['enable_qris']) ? 'qris' : 'manual'))) }}',
              proofPreview: null,
              needsApproval: false,
              setAmount(value) {
                  this.amount = value;
                  this.needsApproval = value >= 1000000;
              }
          }">
        @csrf
        
        <div class="space-y-6 sm:space-y-8">
            <!-- Amount Section -->
            <div class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-white/5">
                <label class="block text-sm sm:text-base font-semibold mb-3 text-white flex items-center gap-2">
                    <x-icon name="dollar" class="w-5 h-5 text-primary" />
                    <span>Jumlah Top Up</span>
                    <span class="text-red-400">*</span>
                </label>
                @php
                    $minTopup = $limits['min_topup_amount'] ?? 10000;
                    $maxTopup = $limits['max_topup_amount'] ?? 10000000;
                @endphp
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/60 font-medium">Rp</span>
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
                           placeholder="0"
                           value="{{ old('amount') }}"
                           class="w-full bg-white/5 border border-white/10 rounded-lg pl-12 pr-4 py-3 sm:py-4 text-lg sm:text-xl font-semibold text-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all touch-target">
                </div>
                @error('amount')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
                
                <!-- Quick Amount Buttons -->
                <div class="mt-4">
                    <p class="text-xs sm:text-sm text-white/60 mb-3">Pilih Jumlah Cepat:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-3">
                        <button type="button" 
                                @click="setAmount(50000)"
                                class="px-3 sm:px-4 py-2 sm:py-2.5 bg-white/5 hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium text-white touch-target">
                            Rp 50rb
                        </button>
                        <button type="button" 
                                @click="setAmount(100000)"
                                class="px-3 sm:px-4 py-2 sm:py-2.5 bg-white/5 hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium text-white touch-target">
                            Rp 100rb
                        </button>
                        <button type="button" 
                                @click="setAmount(250000)"
                                class="px-3 sm:px-4 py-2 sm:py-2.5 bg-white/5 hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium text-white touch-target">
                            Rp 250rb
                        </button>
                        <button type="button" 
                                @click="setAmount(500000)"
                                class="px-3 sm:px-4 py-2 sm:py-2.5 bg-white/5 hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium text-white touch-target">
                            Rp 500rb
                        </button>
                        <button type="button" 
                                @click="setAmount(1000000)"
                                class="px-3 sm:px-4 py-2 sm:py-2.5 bg-white/5 hover:bg-primary/20 hover:border-primary/40 border border-white/10 rounded-lg transition-all text-sm font-medium text-white touch-target col-span-2 sm:col-span-1">
                            Rp 1jt
                        </button>
                    </div>
                </div>
                
                <div class="mt-4 space-y-1">
                    <p class="text-xs text-white/50">Min: Rp {{ number_format($minTopup, 0, ',', '.') }} | Max: Rp {{ number_format($maxTopup, 0, ',', '.') }}</p>
                    <div x-show="needsApproval" 
                         x-transition
                         class="flex items-start gap-2 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg mt-3">
                        <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" />
                        <p class="text-yellow-400 text-xs sm:text-sm">
                            Top-up di atas Rp 1.000.000 memerlukan verifikasi admin (1-2 hari kerja)
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Method Section -->
            <div class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-white/5">
                <label class="block text-sm sm:text-base font-semibold mb-4 text-white flex items-center gap-2">
                    <x-icon name="credit-card" class="w-5 h-5 text-primary" />
                    <span>Metode Pembayaran</span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    @if(isset($featureFlags) && $featureFlags['enable_veripay'])
                    <label class="cursor-pointer group">
                        <input type="radio" 
                               name="payment_method" 
                               value="veripay_qris"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="relative bg-white/5 border-2 rounded-xl p-4 transition-all peer-checked:border-green-500/50 peer-checked:bg-green-500/10 border-white/10 hover:border-green-500/30 group-hover:bg-white/5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30 flex-shrink-0">
                                    <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold text-sm sm:text-base text-white">QRIS (Veripay)</p>
                                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-xs font-medium border border-green-500/30">Auto</span>
                                    </div>
                                    <p class="text-xs text-white/60">Verifikasi otomatis</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center flex-shrink-0">
                                    <svg x-show="$el.previousElementSibling.checked" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    
                    @if(isset($featureFlags) && $featureFlags['enable_bank_transfer'])
                    <label class="cursor-pointer group">
                        <input type="radio" 
                               name="payment_method" 
                               value="bank_transfer"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="relative bg-white/5 border-2 rounded-xl p-4 transition-all peer-checked:border-blue-500/50 peer-checked:bg-blue-500/10 border-white/10 hover:border-blue-500/30 group-hover:bg-white/5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30 flex-shrink-0">
                                    <x-icon name="bank" class="w-5 h-5 text-blue-400" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm sm:text-base text-white mb-1">Transfer Bank</p>
                                    <p class="text-xs text-white/60">Upload bukti transfer</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center flex-shrink-0">
                                    <svg x-show="$el.previousElementSibling.checked" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    
                    @if(isset($featureFlags) && $featureFlags['enable_qris'])
                    <label class="cursor-pointer group">
                        <input type="radio" 
                               name="payment_method" 
                               value="qris"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="relative bg-white/5 border-2 rounded-xl p-4 transition-all peer-checked:border-green-500/50 peer-checked:bg-green-500/10 border-white/10 hover:border-green-500/30 group-hover:bg-white/5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30 flex-shrink-0">
                                    <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm sm:text-base text-white mb-1">QRIS Manual</p>
                                    <p class="text-xs text-white/60">Verifikasi oleh admin</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center flex-shrink-0">
                                    <svg x-show="$el.previousElementSibling.checked" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                    <label class="cursor-pointer group">
                        <input type="radio" 
                               name="payment_method" 
                               value="manual"
                               x-model="paymentMethod"
                               class="hidden peer">
                        <div class="relative bg-white/5 border-2 rounded-xl p-4 transition-all peer-checked:border-primary/50 peer-checked:bg-primary/10 border-white/10 hover:border-primary/30 group-hover:bg-white/5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center border border-primary/30 flex-shrink-0">
                                    <x-icon name="settings" class="w-5 h-5 text-primary" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm sm:text-base text-white mb-1">Manual (Admin)</p>
                                    <p class="text-xs text-white/60">Verifikasi manual</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 border-white/30 peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center flex-shrink-0">
                                    <svg x-show="$el.previousElementSibling.checked" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endif
                </div>
                @error('payment_method')
                <p class="text-red-400 text-sm mt-3 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
            </div>
            
            <!-- Bank Transfer Info -->
            <div x-show="paymentMethod === 'bank_transfer'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-blue-500/20">
                @if(isset($bankAccountInfo) && ($bankAccountInfo['bank_name'] || $bankAccountInfo['bank_account_number']))
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <x-icon name="bank" class="w-5 h-5 text-blue-400" />
                    </div>
                    <h3 class="font-semibold text-blue-400 text-base sm:text-lg">Transfer ke Rekening</h3>
                </div>
                <div class="space-y-3">
                    @if($bankAccountInfo['bank_name'])
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-white/5 rounded-lg">
                        <span class="text-white/60 text-sm flex items-center gap-2">
                            <x-icon name="building" class="w-4 h-4" />
                            Bank
                        </span>
                        <span class="font-semibold text-white">{{ $bankAccountInfo['bank_name'] }}</span>
                    </div>
                    @endif
                    @if($bankAccountInfo['bank_account_number'])
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-white/5 rounded-lg">
                        <span class="text-white/60 text-sm flex items-center gap-2">
                            <x-icon name="credit-card" class="w-4 h-4" />
                            No. Rekening
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold font-mono text-white">{{ $bankAccountInfo['bank_account_number'] }}</span>
                            <button type="button" 
                                    onclick="navigator.clipboard.writeText('{{ $bankAccountInfo['bank_account_number'] }}'); window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Nomor rekening disalin!', type: 'success'}}));"
                                    class="p-1.5 bg-white/5 hover:bg-white/10 rounded-lg transition-colors touch-target"
                                    title="Salin">
                                <x-icon name="copy" class="w-4 h-4 text-primary" />
                            </button>
                        </div>
                    </div>
                    @endif
                    @if($bankAccountInfo['bank_account_name'])
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-white/5 rounded-lg">
                        <span class="text-white/60 text-sm flex items-center gap-2">
                            <x-icon name="user" class="w-4 h-4" />
                            Atas Nama
                        </span>
                        <span class="font-semibold text-white">{{ $bankAccountInfo['bank_account_name'] }}</span>
                    </div>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-3 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                    <p class="text-yellow-400 text-sm">Informasi rekening bank belum dikonfigurasi. Silakan hubungi admin.</p>
                </div>
                @endif
            </div>
            
            <!-- QRIS Manual Info -->
            <div x-show="paymentMethod === 'qris'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-green-500/20">
                @if(isset($bankAccountInfo) && $bankAccountInfo['qris_code'])
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                        <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                    </div>
                    <h3 class="font-semibold text-green-400 text-base sm:text-lg">Scan QRIS</h3>
                </div>
                <div class="flex justify-center">
                    @if(filter_var($bankAccountInfo['qris_code'], FILTER_VALIDATE_URL))
                    <img src="{{ $bankAccountInfo['qris_code'] }}" 
                         alt="QRIS Code" 
                         class="w-full max-w-[280px] rounded-xl border-2 border-white/10 p-4 bg-white">
                    @elseif(str_starts_with($bankAccountInfo['qris_code'], 'data:image'))
                    <img src="{{ $bankAccountInfo['qris_code'] }}" 
                         alt="QRIS Code" 
                         class="w-full max-w-[280px] rounded-xl border-2 border-white/10 p-4 bg-white">
                    @else
                    <div class="bg-white p-4 rounded-xl border-2 border-white/10">
                        <div id="qris-code-topup" class="w-full max-w-[280px]"></div>
                    </div>
                    <script>
                        document.getElementById('qris-code-topup').innerHTML = '<img src="data:image/png;base64,{{ $bankAccountInfo['qris_code'] }}" alt="QRIS" class="w-full">';
                    </script>
                    @endif
                </div>
                @else
                <div class="flex items-center gap-3 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <x-icon name="alert-circle" class="w-5 h-5 text-yellow-400 flex-shrink-0" />
                    <p class="text-yellow-400 text-sm">QRIS code belum dikonfigurasi. Silakan hubungi admin.</p>
                </div>
                @endif
            </div>
            
            <!-- Veripay QRIS Info -->
            <div x-show="paymentMethod === 'veripay_qris'" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-green-500/20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                        <x-icon name="qr-code" class="w-5 h-5 text-green-400" />
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-green-400 text-base sm:text-lg">Pembayaran Otomatis via Veripay</h3>
                    </div>
                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs font-medium border border-green-500/30">Auto Verify</span>
                </div>
                <div class="space-y-2.5 text-sm text-white/80">
                    <div class="flex items-start gap-2.5">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Scan QRIS code yang akan muncul setelah checkout</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Pembayaran akan diverifikasi otomatis oleh sistem</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Saldo akan langsung ditambahkan setelah pembayaran berhasil</span>
                    </div>
                </div>
            </div>
            
            <!-- Proof Upload -->
            <div x-show="paymentMethod === 'bank_transfer'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-white/5">
                <label class="block text-sm sm:text-base font-semibold mb-3 text-white flex items-center gap-2">
                    <x-icon name="image" class="w-5 h-5 text-primary" />
                    <span>Bukti Pembayaran</span>
                    <span class="text-red-400">*</span>
                </label>
                <div class="space-y-3">
                    <input type="file" 
                           name="proof_path" 
                           accept="image/jpeg,image/png,image/jpg,application/pdf"
                           @change="proofPreview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm sm:text-base text-white/80 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all touch-target file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/20 file:text-primary file:cursor-pointer hover:file:bg-primary/30">
                    @error('proof_path')
                    <p class="text-red-400 text-sm flex items-center gap-1">
                        <x-icon name="alert-circle" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                    <p class="text-xs text-white/50">Format: JPEG, PNG, JPG, PDF (Max 2MB)</p>
                    
                    <div x-show="proofPreview" 
                         x-transition
                         class="mt-4">
                        <p class="text-sm mb-2 font-medium text-white/80">Preview:</p>
                        <img :src="proofPreview" 
                             alt="Proof Preview" 
                             class="w-full max-w-md rounded-lg border border-white/10" 
                             x-show="proofPreview && !proofPreview.includes('pdf')">
                        <div x-show="proofPreview && proofPreview.includes('pdf')" 
                             class="flex items-center gap-2 p-4 bg-white/5 rounded-lg border border-white/10">
                            <x-icon name="file-text" class="w-8 h-8 text-primary" />
                            <span class="text-primary font-semibold">PDF File Selected</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-white/5">
                <label class="block text-sm sm:text-base font-semibold mb-3 text-white flex items-center gap-2">
                    <x-icon name="file-text" class="w-5 h-5 text-primary" />
                    <span>Catatan (Opsional)</span>
                </label>
                <textarea name="description" 
                          rows="3"
                          placeholder="Tambahkan catatan untuk top-up ini..."
                          class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm sm:text-base text-white placeholder-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none touch-target">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <x-icon name="info" class="w-5 h-5 text-blue-400" />
                    </div>
                    <h4 class="font-semibold text-blue-400 text-base sm:text-lg">Informasi Penting</h4>
                </div>
                <ul class="text-xs sm:text-sm text-white/80 space-y-2.5">
                    <li class="flex items-start gap-2.5">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di bawah Rp 1.000.000 akan langsung ditambahkan ke wallet</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="clock" class="w-4 h-4 text-yellow-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di atas Rp 1.000.000 memerlukan verifikasi admin (1-2 hari kerja)</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="image" class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" />
                        <span>Pastikan bukti pembayaran jelas dan valid</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="wallet" class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" />
                        <span>Saldo akan ditambahkan setelah pembayaran diverifikasi</span>
                    </li>
                </ul>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" 
                        class="flex-1 px-6 py-3.5 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-all font-semibold text-white touch-target text-base sm:text-lg flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:shadow-primary/30">
                    <x-icon name="check" class="w-5 h-5" />
                    <span>Kirim Permintaan Top Up</span>
                </button>
                <a href="{{ route('wallet.index') }}" 
                   class="px-6 py-3.5 sm:py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all text-center touch-target text-base sm:text-lg flex items-center justify-center gap-2 font-medium text-white">
                    <x-icon name="x" class="w-5 h-5" />
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

                        <p class="text-sm mb-2 font-medium text-white/80">Preview:</p>
                        <img :src="proofPreview" 
                             alt="Proof Preview" 
                             class="w-full max-w-md rounded-lg border border-white/10" 
                             x-show="proofPreview && !proofPreview.includes('pdf')">
                        <div x-show="proofPreview && proofPreview.includes('pdf')" 
                             class="flex items-center gap-2 p-4 bg-white/5 rounded-lg border border-white/10">
                            <x-icon name="file-text" class="w-8 h-8 text-primary" />
                            <span class="text-primary font-semibold">PDF File Selected</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="bg-[#1A1A1C] rounded-xl p-4 sm:p-6 border border-white/5">
                <label class="block text-sm sm:text-base font-semibold mb-3 text-white flex items-center gap-2">
                    <x-icon name="file-text" class="w-5 h-5 text-primary" />
                    <span>Catatan (Opsional)</span>
                </label>
                <textarea name="description" 
                          rows="3"
                          placeholder="Tambahkan catatan untuk top-up ini..."
                          class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm sm:text-base text-white placeholder-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none touch-target">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                    <x-icon name="alert-circle" class="w-4 h-4" />
                    {{ $message }}
                </p>
                @enderror
            </div>
            
            <!-- Info Box -->
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <x-icon name="info" class="w-5 h-5 text-blue-400" />
                    </div>
                    <h4 class="font-semibold text-blue-400 text-base sm:text-lg">Informasi Penting</h4>
                </div>
                <ul class="text-xs sm:text-sm text-white/80 space-y-2.5">
                    <li class="flex items-start gap-2.5">
                        <x-icon name="check-circle" class="w-4 h-4 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di bawah Rp 1.000.000 akan langsung ditambahkan ke wallet</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="clock" class="w-4 h-4 text-yellow-400 flex-shrink-0 mt-0.5" />
                        <span>Top-up di atas Rp 1.000.000 memerlukan verifikasi admin (1-2 hari kerja)</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="image" class="w-4 h-4 text-blue-400 flex-shrink-0 mt-0.5" />
                        <span>Pastikan bukti pembayaran jelas dan valid</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <x-icon name="wallet" class="w-4 h-4 text-primary flex-shrink-0 mt-0.5" />
                        <span>Saldo akan ditambahkan setelah pembayaran diverifikasi</span>
                    </li>
                </ul>
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" 
                        class="flex-1 px-6 py-3.5 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-all font-semibold text-white touch-target text-base sm:text-lg flex items-center justify-center gap-2 shadow-lg shadow-primary/20 hover:shadow-primary/30">
                    <x-icon name="check" class="w-5 h-5" />
                    <span>Kirim Permintaan Top Up</span>
                </button>
                <a href="{{ route('wallet.index') }}" 
                   class="px-6 py-3.5 sm:py-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all text-center touch-target text-base sm:text-lg flex items-center justify-center gap-2 font-medium text-white">
                    <x-icon name="x" class="w-5 h-5" />
                    <span>Batal</span>
                </a>
            </div>
        </div>
    </form>
</div>
@endsection