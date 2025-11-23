@extends('layouts.app')

@section('title', 'Detail Pesanan - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('orders.index') }}" class="text-primary hover:underline flex items-center space-x-2 touch-target text-sm sm:text-base">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>
    
    <!-- Banner Rating Reminder (Menonjol) -->
    @if($order->canBeRated())
    <div class="mb-4 sm:mb-6 glass p-4 sm:p-6 rounded-lg border-2 border-yellow-500/50 bg-gradient-to-r from-yellow-500/10 via-yellow-500/5 to-transparent" 
         x-data="{ show: true }"
         x-show="show"
         x-transition>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-yellow-500/20 flex items-center justify-center">
                    <x-icon name="star" class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-400" />
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-yellow-400 mb-2 text-lg sm:text-xl flex items-center gap-2">
                    <span>Beri Rating untuk Pesanan Ini</span>
                </h3>
                <p class="text-white/90 mb-4 text-sm sm:text-base">
                    Pesanan Anda telah selesai! Bagikan pengalaman Anda dengan memberikan rating dan ulasan. Ini akan membantu seller lain dalam membuat keputusan.
                </p>
                <a href="{{ route('ratings.create', $order) }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-yellow-500/20">
                    <x-icon name="star" class="w-5 h-5" />
                    <span>Beri Rating Sekarang</span>
                </a>
            </div>
            <button @click="show = false" 
                    class="text-white/60 hover:text-white flex-shrink-0 transition-colors touch-target">
                <x-icon name="x" class="w-5 h-5" />
            </button>
        </div>
    </div>
    @endif

    <!-- Alert untuk upload bukti pembayaran -->
    @if(session('upload_proof_required') || ($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending' && !$order->payment->proof_path))
    <div class="mb-4 sm:mb-6 glass p-4 sm:p-6 rounded-lg border-2 border-yellow-500/50 bg-yellow-500/10" 
         x-data="{ show: true }"
         x-show="show"
         x-transition>
        <div class="flex items-start gap-4">
            <x-icon name="alert" class="w-8 h-8 text-yellow-400 flex-shrink-0" />
            <div class="flex-1">
                <h3 class="font-bold text-yellow-400 mb-2 text-lg">Upload Bukti Pembayaran Diperlukan!</h3>
                <p class="text-white/90 mb-3">
                    Anda menggunakan metode pembayaran <strong>{{ $order->payment->getMethodDisplayName() }}</strong>. 
                    Silakan upload bukti pembayaran Anda untuk melanjutkan proses verifikasi.
                </p>
                @if($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending')
                <label class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold transition-colors cursor-pointer inline-block"
                       x-data="{ uploading: false }"
                       @change="
                           uploading = true;
                           const form = new FormData();
                           form.append('proof_path', $event.target.files[0]);
                           form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                           
                           fetch('{{ route('payments.upload', $order->payment) }}', {
                               method: 'POST',
                               body: form,
                               headers: {
                                   'X-Requested-With': 'XMLHttpRequest'
                               }
                           })
                           .then(response => {
                               if (response.ok) {
                                   window.location.reload();
                               } else {
                                   return response.json().then(data => {
                                       throw new Error(data.message || 'Upload gagal');
                                   });
                               }
                           })
                               .catch(error => {
                                   window.dispatchEvent(new CustomEvent('toast', { 
                                       detail: { 
                                           message: error.message || 'Upload gagal. Silakan coba lagi.', 
                                           type: 'error' 
                                       } 
                                   }));
                                   uploading = false;
                               });
                       ">
                    <input type="file" 
                           name="proof_path" 
                           accept="image/jpeg,image/png,image/jpg,application/pdf" 
                           class="hidden"
                           x-bind:disabled="uploading">
                    <span x-show="!uploading">📤 Upload Bukti Pembayaran Sekarang</span>
                    <span x-show="uploading" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    </span>
                </label>
                @endif
            </div>
            <button @click="show = false" class="text-white/60 hover:text-white flex-shrink-0">✕</button>
        </div>
    </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Order Info -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-start gap-3 sm:gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold mb-2 break-words">Order #{{ $order->order_number }}</h1>
                        <p class="text-white/60 text-sm sm:text-base">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        @include('components.order-status-badge', ['status' => $order->status])
                    </div>
                </div>
                
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3">Detail Item</h3>
                    <div class="flex items-center space-x-4">
                        @if($order->type === 'product' && $order->product)
                            @if($order->product->image)
                            <img src="{{ asset('storage/' . $order->product->image) }}" 
                                 alt="{{ $order->product->title }}" 
                                 class="w-20 h-20 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold">{{ $order->product->title }}</h4>
                                <p class="text-white/60 text-sm">{{ $order->product->category }}</p>
                            </div>
                        @elseif($order->type === 'service' && $order->service)
                            @if($order->service->image)
                            <img src="{{ asset('storage/' . $order->service->image) }}" 
                                 alt="{{ $order->service->title }}" 
                                 class="w-20 h-20 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold">{{ $order->service->title }}</h4>
                                <p class="text-white/60 text-sm">Durasi: {{ $order->service->duration_hours }} jam</p>
                            </div>
                        @endif
                        <div class="text-right">
                            <p class="text-2xl font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($order->notes)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-2">Catatan</h3>
                    <p class="text-white/70">{{ $order->notes }}</p>
                </div>
                @endif
                
                <!-- Task File (for Service Orders) -->
                @if($order->type === 'service' && $order->task_file_path)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <x-icon name="file-text" class="w-5 h-5 text-primary" />
                        File Tugas
                    </h3>
                    @php
                        $taskFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->task_file_path);
                        $taskFileName = basename($order->task_file_path);
                        $taskFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->task_file_path);
                    @endphp
                    
                    @if($taskFileExists)
                    <div class="glass p-4 rounded-lg">
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                            <x-icon name="file-text" class="w-6 h-6 text-primary" />
                            <div class="flex-1">
                                <p class="font-semibold">File Tugas dari Buyer</p>
                                <p class="text-xs text-white/60">{{ $taskFileName }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @if($isSeller || $isAdmin)
                            <a href="{{ route('orders.downloadTask', $order) }}" 
                               class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                <x-icon name="download" class="w-5 h-5 inline mr-2" />
                                Download File Tugas
                            </a>
                            @endif
                            @if($isOwner)
                            <p class="text-xs text-white/60 text-center">
                                File ini dapat diakses oleh seller untuk mengerjakan tugas Anda.
                            </p>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                        <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                            <x-icon name="alert" class="w-5 h-5" />
                            File Tugas Tidak Ditemukan
                        </p>
                        <p class="text-sm text-yellow-300/80">File tugas tidak dapat ditemukan di storage. Silakan hubungi admin.</p>
                    </div>
                    @endif
                </div>
                @endif
                
                <!-- Deliverable File (for Service Orders) -->
                @if($order->type === 'service' && $order->deliverable_path && !($order->status === 'waiting_confirmation' && $isOwner))
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <x-icon name="package" class="w-5 h-5 text-primary" />
                        Hasil Pekerjaan
                    </h3>
                    @php
                        $deliverableFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->deliverable_path);
                        $deliverableFileName = basename($order->deliverable_path);
                        $deliverableFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                        $fileExtension = strtolower(pathinfo($deliverableFileName, PATHINFO_EXTENSION));
                        $canPreview = in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                    @endphp
                    
                    @if($deliverableFileExists)
                    <div class="glass p-4 rounded-lg">
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                            <x-icon name="file-text" class="w-6 h-6 text-green-400" />
                            <div class="flex-1">
                                <p class="font-semibold">File Hasil Pekerjaan</p>
                                <p class="text-xs text-white/60">{{ $deliverableFileName }}</p>
                            </div>
                        </div>
                        
                        <!-- Preview Section (for PDF and Images) -->
                        @if($canPreview && ($isOwner || $isAdmin))
                        <div class="mb-4 p-3 bg-white/5 rounded-lg border border-white/10">
                            <p class="text-xs text-white/60 mb-2 flex items-center gap-1">
                                <x-icon name="eye" class="w-3 h-3" />
                                Preview:
                            </p>
                            <div class="rounded-lg overflow-hidden bg-white/5 max-h-96 overflow-y-auto">
                                @if($fileExtension === 'pdf')
                                    <iframe src="{{ $deliverableFileUrl }}#toolbar=0" 
                                            class="w-full h-96 border-0"
                                            style="min-height: 400px;">
                                    </iframe>
                                @else
                                    <img src="{{ $deliverableFileUrl }}" 
                                         alt="Preview {{ $deliverableFileName }}"
                                         class="w-full h-auto object-contain">
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="space-y-2">
                            @if($isSeller || $isAdmin)
                            <div class="flex gap-2">
                                <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                                   target="_blank"
                                   class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                    <x-icon name="download" class="w-5 h-5 inline mr-2" />
                                    Download
                                </a>
                                 <button type="button"
                                        onclick="
                                            const modal = document.getElementById('delete-deliverable-modal');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        "
                                        class="flex-shrink-0 px-4 py-2 glass glass-hover rounded-lg text-red-400">
                                    <x-icon name="x" class="w-4 h-4 inline mr-1" />
                                    Hapus
                                </button>
                                
                                <!-- Delete Deliverable Confirmation Modal -->
                                <x-confirm-modal 
                                    id="delete-deliverable-modal"
                                    title="Hapus Hasil Pekerjaan"
                                    message="Apakah Anda yakin ingin menghapus hasil pekerjaan ini? Buyer akan mendapat notifikasi."
                                    confirm-text="Ya, Hapus"
                                    cancel-text="Batal"
                                    type="danger" />
                                
                                <form id="delete-deliverable-form" method="POST" action="{{ route('orders.deleteDeliverable', $order) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const confirmBtn = document.getElementById('delete-deliverable-modal-confirm-btn');
                                        const modal = document.getElementById('delete-deliverable-modal');
                                        const form = document.getElementById('delete-deliverable-form');
                                        
                                        if (confirmBtn && modal && form) {
                                            confirmBtn.addEventListener('click', function() {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                                form.submit();
                                            });
                                        }
                                    });
                                </script>
                            </div>
                            <p class="text-xs text-white/60 text-center">
                                <span class="flex items-center gap-1">
                                    <x-icon name="lightbulb" class="w-3 h-3" />
                                    Upload ulang untuk mengganti file hasil pekerjaan
                                </span>
                            </p>
                            @elseif($isOwner && $order->payment && $order->payment->status === 'verified')
                            <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                               target="_blank"
                               class="block px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-center font-semibold transition-colors">
                                <x-icon name="download" class="w-5 h-5 inline mr-2" />
                                Download Hasil Pekerjaan
                            </a>
                            @elseif($isOwner)
                            <p class="text-xs text-yellow-400 text-center">
                                <span class="flex items-center gap-1">
                                    <x-icon name="clock" class="w-3 h-3" />
                                    Hasil pekerjaan akan tersedia setelah pembayaran diverifikasi
                                </span>
                            </p>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                        <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                            <x-icon name="alert" class="w-5 h-5" />
                            File Hasil Pekerjaan Tidak Ditemukan
                        </p>
                        <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan hubungi admin.</p>
                    </div>
                    @endif
                </div>
                @endif
            </div>
            
            <!-- Payment Info -->
            @if($order->payment)
            <div class="glass p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-white/60">Metode Pembayaran</span>
                        <span class="font-semibold">{{ $order->payment->getMethodDisplayName() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Status Pembayaran</span>
                        @if($order->payment->status === 'verified')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Verified</span>
                        @elseif($order->payment->status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">Pending</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Rejected</span>
                        @endif
                    </div>
                    
                    <!-- Bank Account Info untuk Bank Transfer -->
                    @if($order->payment->method === 'bank_transfer' && $bankAccountInfo && $bankAccountInfo['bank_account_number'])
                    <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <h3 class="font-semibold text-blue-400 mb-3 flex items-center gap-2">
                            <x-icon name="bank" class="w-5 h-5 text-blue-400" />
                            <span>Informasi Rekening Bank</span>
                        </h3>
                        <div class="space-y-2 text-sm">
                            @if($bankAccountInfo['bank_name'])
                            <div class="flex justify-between">
                                <span class="text-white/60">Bank</span>
                                <span class="font-semibold">{{ $bankAccountInfo['bank_name'] }}</span>
                            </div>
                            @endif
                            @if($bankAccountInfo['bank_account_number'])
                            <div class="flex justify-between">
                                <span class="text-white/60">Nomor Rekening</span>
                                <span class="font-semibold font-mono text-primary">{{ $bankAccountInfo['bank_account_number'] }}</span>
                            </div>
                            @endif
                            @if($bankAccountInfo['bank_account_name'])
                            <div class="flex justify-between">
                                <span class="text-white/60">Nama Pemilik</span>
                                <span class="font-semibold">{{ $bankAccountInfo['bank_account_name'] }}</span>
                            </div>
                            @endif
                        </div>
                        <p class="text-xs text-white/60 mt-3 pt-3 border-t border-white/10">
                            <span class="flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                Transfer sesuai dengan nominal pesanan: <strong class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                            </span>
                        </p>
                    </div>
                    @endif
                    
                    <!-- QRIS Info -->
                    @if($order->payment->method === 'qris' && $bankAccountInfo && $bankAccountInfo['qris_code'])
                    <div class="mt-4 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                        <h3 class="font-semibold text-green-400 mb-3 flex items-center gap-2">
                            <x-icon name="mobile" class="w-5 h-5 text-green-400" />
                            <span>QRIS Payment</span>
                        </h3>
                        <div class="text-center">
                            <img src="{{ $bankAccountInfo['qris_code'] }}" alt="QRIS Code" class="mx-auto max-w-xs rounded-lg mb-3">
                            <p class="text-xs text-white/60">
                                Scan QR code di atas untuk melakukan pembayaran
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Warning untuk upload bukti -->
                    @if(in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending' && !$order->payment->proof_path)
                    <div class="mt-4 p-4 bg-yellow-500/20 border-2 border-yellow-500/30 rounded-lg">
                        <div class="flex items-start gap-3">
                            <x-icon name="alert" class="w-6 h-6 text-yellow-400" />
                            <div class="flex-1">
                                <p class="font-semibold text-yellow-400 mb-1">Upload Bukti Pembayaran Diperlukan!</p>
                                <p class="text-sm text-white/80">
                                    Anda menggunakan metode {{ $order->payment->getMethodDisplayName() }}. 
                                    Silakan upload bukti pembayaran Anda untuk proses verifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->payment->verified_at)
                    <div class="flex justify-between">
                        <span class="text-white/60">Diverifikasi pada</span>
                        <span>{{ $order->payment->verified_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($order->payment->proof_path)
                    <div class="mt-4">
                        <p class="text-white/60 mb-2">Bukti Pembayaran</p>
                        @php
                            $proofUrl = $order->payment->getProofUrl();
                            $fileExists = $proofUrl !== null && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->payment->proof_path);
                        @endphp
                        
                        @if($fileExists && $order->payment->isProofImage())
                        <div class="glass p-4 rounded-lg">
                            <img src="{{ $proofUrl }}" 
                                 alt="Payment Proof" 
                                 class="max-w-md w-full rounded-lg border-2 border-white/10 cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="window.open('{{ $proofUrl }}', '_blank')"
                                 title="Klik untuk membuka di tab baru"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none;" class="p-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                                <p class="text-sm flex items-center gap-1">
                                    <x-icon name="alert" class="w-4 h-4" />
                                    Gambar tidak dapat dimuat. <a href="{{ $proofUrl }}" target="_blank" class="underline">Klik di sini untuk membuka</a>
                                </p>
                            </div>
                            <a href="{{ $proofUrl }}" 
                               target="_blank" 
                               class="text-primary hover:underline text-sm mt-2 inline-block">
                                <x-icon name="link" class="w-4 h-4 inline mr-1" />
                                Buka di tab baru
                            </a>
                        </div>
                        @elseif($fileExists && $order->payment->isProofPdf())
                        <div class="glass p-4 rounded-lg">
                            <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                                <x-icon name="file-text" class="w-8 h-8 text-primary" />
                                <div class="flex-1">
                                    <p class="font-semibold">File PDF</p>
                                    <p class="text-xs text-white/60">{{ basename($order->payment->proof_path) }}</p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="{{ $proofUrl }}" 
                                   target="_blank" 
                                   class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                    <x-icon name="download" class="w-4 h-4 inline mr-1" />
                                    Download / Buka PDF
                                </a>
                                <iframe src="{{ $proofUrl }}" 
                                        class="w-full h-96 rounded-lg border-2 border-white/10"
                                        frameborder="0"
                                        onerror="this.style.display='none';">
                                </iframe>
                                <p class="text-xs text-white/60 text-center">
                                    Jika PDF tidak muncul, <a href="{{ $proofUrl }}" target="_blank" class="text-primary hover:underline">klik di sini untuk membuka di tab baru</a>
                                </p>
                            </div>
                        </div>
                        @elseif($fileExists)
                        <div class="glass p-4 rounded-lg">
                            <a href="{{ $proofUrl }}" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-primary/20 hover:bg-primary/30 rounded-lg transition-colors">
                                <x-icon name="file-text" class="w-6 h-6 text-primary" />
                                <div class="flex-1">
                                    <p class="font-semibold">Bukti Pembayaran</p>
                                    <p class="text-xs text-white/60">{{ basename($order->payment->proof_path) }}</p>
                                </div>
                                <span class="text-primary">→</span>
                            </a>
                        </div>
                        @else
                        <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                            <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                                <x-icon name="alert" class="w-5 h-5" />
                                File Bukti Pembayaran Tidak Ditemukan
                            </p>
                            <p class="text-sm text-yellow-300/80">File bukti pembayaran tidak dapat ditemukan di storage. Silakan hubungi admin atau upload ulang bukti pembayaran.</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Waiting Confirmation Section (Buyer Only) -->
            @if($order->type === 'service' && $order->status === 'waiting_confirmation' && $isOwner)
            <div class="glass p-6 rounded-xl border-2 border-primary/50 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 mb-6">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/30 flex items-center justify-center">
                        <x-icon name="clock" class="w-6 h-6 text-primary" />
                    </div>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-1 text-white">Review Hasil Pekerjaan</h2>
                        <p class="text-sm text-white/70">Seller telah mengupload hasil pekerjaan. Silakan review dan konfirmasi.</p>
                    </div>
                </div>
                
                <!-- Deliverable Preview (if exists) -->
                @if($order->deliverable_path)
                @php
                    $deliverableFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->deliverable_path);
                    $deliverableFileName = basename($order->deliverable_path);
                    $deliverableFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                    $fileExtension = strtolower(pathinfo($deliverableFileName, PATHINFO_EXTENSION));
                    $canPreview = in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                @endphp
                
                @if($deliverableFileExists)
                <div class="mb-4 p-4 bg-white/5 rounded-lg border border-white/10">
                    <div class="flex items-center gap-3 mb-3">
                        <x-icon name="file-text" class="w-6 h-6 text-green-400" />
                        <div class="flex-1">
                            <p class="font-semibold">File Hasil Pekerjaan</p>
                            <p class="text-xs text-white/60">{{ $deliverableFileName }}</p>
                        </div>
                    </div>
                    
                    <!-- Preview Section (for PDF and Images) -->
                    @if($canPreview)
                    <div class="mb-3 p-3 bg-white/5 rounded-lg border border-white/10">
                        <p class="text-xs text-white/60 mb-2 flex items-center gap-1">
                            <x-icon name="eye" class="w-3 h-3" />
                            Preview:
                        </p>
                        <div class="rounded-lg overflow-hidden bg-white/5 max-h-96 overflow-y-auto">
                            @if($fileExtension === 'pdf')
                                <iframe src="{{ $deliverableFileUrl }}#toolbar=0" 
                                        class="w-full h-96 border-0"
                                        style="min-height: 400px;">
                                </iframe>
                            @else
                                <img src="{{ $deliverableFileUrl }}" 
                                     alt="Preview {{ $deliverableFileName }}"
                                     class="w-full h-auto object-contain">
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                       target="_blank"
                       class="block px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-center font-semibold transition-colors">
                        <x-icon name="download" class="w-5 h-5 inline mr-2" />
                        Download Hasil Pekerjaan
                    </a>
                </div>
                @else
                <div class="mb-4 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                    <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                        <x-icon name="alert" class="w-5 h-5" />
                        File Hasil Pekerjaan Tidak Ditemukan
                    </p>
                    <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan hubungi seller atau admin.</p>
                </div>
                @endif
                @endif
                
                <!-- Countdown Timer -->
                @if($order->auto_complete_at)
                <div class="mb-4 p-3 bg-white/5 rounded-lg border border-white/10" 
                     x-data="{
                         timeLeft: {{ $order->auto_complete_at->diffInSeconds(now()) }},
                         init() {
                             setInterval(() => {
                                 if (this.timeLeft > 0) {
                                     this.timeLeft--;
                                 } else {
                                     this.timeLeft = 0;
                                 }
                             }, 1000);
                         },
                         get hours() {
                             return Math.floor(this.timeLeft / 3600);
                         },
                         get minutes() {
                             return Math.floor((this.timeLeft % 3600) / 60);
                         },
                         get seconds() {
                             return this.timeLeft % 60;
                         },
                         get formatted() {
                             if (this.timeLeft <= 0) return 'Waktu habis - Order akan otomatis selesai';
                             return `${String(this.hours).padStart(2, '0')}:${String(this.minutes).padStart(2, '0')}:${String(this.seconds).padStart(2, '0')}`;
                         }
                     }">
                    <div class="flex items-center gap-2">
                        <x-icon name="timer" class="w-4 h-4 text-yellow-400" />
                        <span class="text-xs text-white/60">Waktu tersisa untuk review:</span>
                        <span class="text-lg font-bold text-yellow-400" x-text="formatted"></span>
                    </div>
                    <p class="text-xs text-white/50 mt-1">Jika tidak ada respon dalam waktu ini, order akan otomatis diselesaikan.</p>
                </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Confirm Completion Button -->
                    <button type="button"
                            onclick="
                                const modal = document.getElementById('confirm-completion-modal');
                                if (modal) {
                                    modal.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                            "
                            class="px-6 py-4 bg-green-500 hover:bg-green-600 rounded-lg transition-all font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-green-500/30">
                        <x-icon name="check" class="w-5 h-5" />
                        <span>Terima Hasil</span>
                    </button>
                    
                    <!-- Request Revision Button -->
                    <button type="button"
                            onclick="
                                const modal = document.getElementById('request-revision-modal');
                                if (modal) {
                                    modal.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                            "
                            class="px-6 py-4 bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/50 text-orange-400 rounded-lg transition-all font-semibold flex items-center justify-center gap-2">
                        <x-icon name="refresh" class="w-5 h-5" />
                        <span>Minta Revisi</span>
                    </button>
                </div>
                
                <!-- Confirm Completion Modal with Rating -->
                <div id="confirm-completion-modal" 
                     class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4"
                     x-data="{ 
                         rating: 0,
                         comment: '',
                         showRating: true
                     }">
                    <div class="bg-dark border border-white/20 rounded-xl p-6 max-w-md w-full max-h-[90vh] overflow-y-auto"
                         @click.away="document.getElementById('confirm-completion-modal').style.display = 'none'; document.body.style.overflow = '';">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <x-icon name="check" class="w-6 h-6 text-green-400" />
                            Konfirmasi Pesanan Selesai
                        </h3>
                        
                        <p class="text-white/70 mb-4">Apakah Anda puas dengan hasil pekerjaan? (Opsional: Beri rating)</p>
                        
                        <!-- Rating Section (Optional) -->
                        <div class="mb-4" x-show="showRating">
                            <label class="block text-sm font-medium mb-2">Rating (Opsional)</label>
                            <div class="flex items-center gap-2 mb-2">
                                <template x-for="i in 5" :key="i">
                                    <button type="button"
                                            @click="rating = i"
                                            :class="i <= rating ? 'text-yellow-400' : 'text-white/30'"
                                            class="text-2xl hover:scale-110 transition-transform">
                                        ★
                                    </button>
                                </template>
                            </div>
                            <textarea x-model="comment"
                                      placeholder="Tulis komentar (opsional)"
                                      rows="3"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm mt-2"></textarea>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="document.getElementById('confirm-completion-modal').style.display = 'none'; document.body.style.overflow = '';"
                                    class="flex-1 px-4 py-2 glass glass-hover rounded-lg font-semibold">
                                Batal
                            </button>
                            <form id="confirm-completion-form" method="POST" action="{{ route('orders.confirm', $order) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="rating" x-model="rating">
                                <input type="hidden" name="comment" x-model="comment">
                                <button type="submit"
                                        class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg font-semibold">
                                    Konfirmasi Selesai
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Request Revision Modal -->
                <div id="request-revision-modal" 
                     class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                    <div class="bg-dark border border-white/20 rounded-xl p-6 max-w-md w-full"
                         @click.away="document.getElementById('request-revision-modal').style.display = 'none'; document.body.style.overflow = '';">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <x-icon name="refresh" class="w-6 h-6 text-orange-400" />
                            Minta Revisi
                        </h3>
                        
                        <form method="POST" action="{{ route('orders.requestRevision', $order) }}">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Alasan Revisi *</label>
                                <textarea name="revision_notes" 
                                          rows="4"
                                          required
                                          placeholder="Jelaskan bagian mana yang perlu direvisi..."
                                          class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="button"
                                        onclick="document.getElementById('request-revision-modal').style.display = 'none'; document.body.style.overflow = '';"
                                        class="flex-1 px-4 py-2 glass glass-hover rounded-lg font-semibold">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg font-semibold">
                                    Kirim Permintaan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            
             <!-- Order Management Controls (Seller/Admin Only) -->
            <x-order-progress-control :order="$order" />
            
            <!-- Actions -->
            <div class="glass p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Aksi</h2>
                <div class="flex flex-wrap gap-3">
                    @if($order->type === 'product' && $order->status === 'completed')
                        @php
                            $canDownload = $order->product && $order->product->file_path;
                            $paymentVerified = $order->payment && $order->payment->status === 'verified';
                        @endphp
                        
                        @if($canDownload && $paymentVerified)
                        <a href="{{ route('products.download', $order->product) }}" 
                           class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                            <x-icon name="download" class="w-5 h-5 inline mr-2" />
                            Download File Produk
                        </a>
                        @elseif(!$canDownload)
                        <div class="px-6 py-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                            <p class="font-semibold flex items-center gap-2">
                                <x-icon name="alert" class="w-5 h-5" />
                                File belum tersedia
                            </p>
                            <p class="text-sm text-yellow-300/80 mt-1">Seller belum mengupload file untuk produk ini. Silakan hubungi seller atau admin.</p>
                        </div>
                        @elseif(!$paymentVerified)
                        <div class="px-6 py-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                            <p class="font-semibold">⏳ Menunggu verifikasi pembayaran</p>
                            <p class="text-sm text-yellow-300/80 mt-1">File akan tersedia setelah pembayaran diverifikasi oleh admin.</p>
                        </div>
                        @endif
                    @endif
                    
                    @if($order->type === 'service' && $order->status === 'completed')
                        @php
                            // Refresh order to get latest deliverable_path
                            $order->refresh();
                            $canDownloadDeliverable = $order->deliverable_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                            $paymentVerified = $order->payment && $order->payment->status === 'verified';
                        @endphp
                        
                        @if($canDownloadDeliverable && $paymentVerified)
                        <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                           target="_blank"
                           class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-lg transition-colors font-semibold">
                            <x-icon name="download" class="w-5 h-5 inline mr-2" />
                            Download Hasil Pekerjaan
                        </a>
                        @elseif(!$canDownloadDeliverable)
                        <div class="px-6 py-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                            <p class="font-semibold flex items-center gap-2">
                                <x-icon name="alert" class="w-5 h-5" />
                                Hasil pekerjaan belum tersedia
                            </p>
                            <p class="text-sm text-yellow-300/80 mt-1">Seller belum mengupload hasil pekerjaan. Silakan hubungi seller atau admin.</p>
                        </div>
                        @elseif(!$paymentVerified)
                        <div class="px-6 py-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                            <p class="font-semibold flex items-center gap-2">
                                <x-icon name="clock" class="w-5 h-5" />
                                Menunggu verifikasi pembayaran
                            </p>
                            <p class="text-sm text-yellow-300/80 mt-1">Hasil pekerjaan akan tersedia setelah pembayaran diverifikasi oleh admin.</p>
                        </div>
                        @endif
                    @endif
                    
                    @if($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending')
                    <label class="px-6 py-3 bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 rounded-lg font-semibold border border-yellow-500/30 cursor-pointer inline-block"
                           x-data="{ uploading: false }"
                           @change="
                               uploading = true;
                               const form = new FormData();
                               form.append('proof_path', $event.target.files[0]);
                               form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                               
                               fetch('{{ route('payments.upload', $order->payment) }}', {
                                   method: 'POST',
                                   body: form,
                                   headers: {
                                       'X-Requested-With': 'XMLHttpRequest'
                                   }
                               })
                               .then(response => {
                                   if (response.ok) {
                                       window.location.reload();
                                   } else {
                                       return response.json().then(data => {
                                           throw new Error(data.message || 'Upload gagal');
                                       });
                                   }
                               })
                               .catch(error => {
                                   window.dispatchEvent(new CustomEvent('toast', { 
                                       detail: { 
                                           message: error.message || 'Upload gagal. Silakan coba lagi.', 
                                           type: 'error' 
                                       } 
                                   }));
                                   uploading = false;
                               });
                           ">
                        <input type="file" 
                               name="proof_path" 
                               accept="image/jpeg,image/png,image/jpg,application/pdf" 
                               class="hidden"
                               x-bind:disabled="uploading">
                        <span x-show="!uploading">📤 Upload Bukti Pembayaran</span>
                        <span x-show="uploading" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Uploading...
                        </span>
                    </label>
                    @endif
                    
                    @if($order->canBeRated())
                    <a href="{{ route('ratings.create', $order) }}" 
                       class="px-6 py-3 bg-gradient-to-r from-yellow-500/20 to-yellow-600/20 hover:from-yellow-500/30 hover:to-yellow-600/30 border-2 border-yellow-500/50 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2">
                        <x-icon name="star" class="w-5 h-5 text-yellow-400" />
                        <span>Beri Rating</span>
                    </a>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('orders.updateStatus', $order) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" 
                                class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar: Timeline -->
        <div class="lg:col-span-1">
            <div class="glass p-6 rounded-lg sticky top-20">
                <h2 class="text-xl font-semibold mb-4">Timeline Pesanan</h2>
                @include('components.order-timeline', ['timeline' => $timeline])
            </div>
        </div>
    </div>
    
</div>
@endsection

