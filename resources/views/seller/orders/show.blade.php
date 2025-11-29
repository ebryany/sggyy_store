@extends('seller.layouts.dashboard')

@section('title', 'Detail Pesanan - Seller Dashboard - Ebrystoree')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('seller.orders.index') }}" class="text-primary hover:underline flex items-center space-x-2 touch-target text-sm sm:text-base">
            <x-icon name="arrow-left" class="w-4 h-4 sm:w-5 sm:h-5" />
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Escrow Status Card (Seller View) -->
            @if($order->escrow)
                @include('components.escrow-status-card-seller', ['order' => $order, 'escrow' => $order->escrow])
            @endif
            
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
                            @if($order->product->image && $order->product->image_url)
                            <img src="{{ $order->product->image_url }}" 
                                 alt="{{ $order->product->title }}" 
                                 class="w-20 h-20 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold">{{ $order->product->title }}</h4>
                                <p class="text-white/60 text-sm">{{ $order->product->category }}</p>
                            </div>
                        @elseif($order->type === 'service' && $order->service)
                            @if($order->service->image && $order->service->image_url)
                            <img src="{{ $order->service->image_url }}" 
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
                
                <!-- Buyer Info -->
                @if($order->user)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <x-icon name="user" class="w-5 h-5 text-primary" />
                        Informasi Buyer
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-white/60">Nama</span>
                            <span class="font-semibold">{{ $order->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Email</span>
                            <span class="font-semibold">{{ $order->user->email }}</span>
                        </div>
                        @if($order->user->phone)
                        <div class="flex justify-between">
                            <span class="text-white/60">Telepon</span>
                            <span class="font-semibold">{{ $order->user->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($order->notes)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-2">Catatan dari Buyer</h3>
                    <p class="text-white/70">{{ $order->notes }}</p>
                </div>
                @endif
                
                <!-- Task File (for Service Orders) -->
                @if($order->type === 'service' && $order->task_file_path)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <x-icon name="file-text" class="w-5 h-5 text-primary" />
                        File Tugas dari Buyer
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
                                <p class="font-semibold">{{ $taskFileName }}</p>
                                <p class="text-xs text-white/60">File tugas dari buyer</p>
                            </div>
                        </div>
                        <a href="{{ route('orders.downloadTask', $order) }}" 
                           class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                            <x-icon name="download" class="w-5 h-5 inline mr-2" />
                            Download File Tugas
                        </a>
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
                @if($order->type === 'service' && $order->deliverable_path)
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <x-icon name="package" class="w-5 h-5 text-primary" />
                        Hasil Pekerjaan
                    </h3>
                    @php
                        $deliverableFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->deliverable_path);
                        $deliverableFileName = basename($order->deliverable_path);
                        $deliverableFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                    @endphp
                    
                    @if($deliverableFileExists)
                    <div class="glass p-4 rounded-lg">
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                            <x-icon name="file-text" class="w-6 h-6 text-green-400" />
                            <div class="flex-1">
                                <p class="font-semibold">{{ $deliverableFileName }}</p>
                                <p class="text-xs text-white/60">File hasil pekerjaan yang telah diupload</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                               target="_blank"
                               class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                <x-icon name="download" class="w-5 h-5 inline mr-2" />
                                Download
                            </a>
                            <button type="button"
                                    onclick="document.querySelector('input[name=deliverable]')?.click()"
                                    class="px-4 py-2 glass glass-hover rounded-lg text-primary">
                                <x-icon name="edit" class="w-4 h-4 inline mr-1" />
                                Ganti File
                            </button>
                        </div>
                        <p class="text-xs text-white/60 text-center mt-2">
                            <span class="flex items-center justify-center gap-1">
                                <x-icon name="lightbulb" class="w-3 h-3" />
                                Upload ulang untuk mengganti file hasil pekerjaan
                            </span>
                        </p>
                    </div>
                    @else
                    <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                        <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                            <x-icon name="alert" class="w-5 h-5" />
                            File Hasil Pekerjaan Tidak Ditemukan
                        </p>
                        <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan upload ulang.</p>
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
                    
                    @if($order->payment->verified_at)
                    <div class="flex justify-between">
                        <span class="text-white/60">Diverifikasi pada</span>
                        <span>{{ $order->payment->verified_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($order->payment->proof_path)
                    <div class="mt-4">
                        <p class="text-white/60 mb-2">Bukti Pembayaran dari Buyer</p>
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
                                 title="Klik untuk membuka di tab baru">
                            <a href="{{ $proofUrl }}" 
                               target="_blank" 
                               class="text-primary hover:underline text-sm mt-2 inline-block">
                                <x-icon name="link" class="w-4 h-4 inline mr-1" />
                                Buka di tab baru
                            </a>
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
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- 🔒 REKBER FLOW: Send Product Section (for Product Orders) -->
            @if($order->type === 'product' && $order->status === 'processing')
            <div class="glass p-4 sm:p-6 rounded-lg border-2 border-primary/30">
                <h3 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="package" class="w-6 h-6 text-primary" />
                    Kirim Produk
                </h3>
                
                <div class="mb-4 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                    <p class="text-sm text-blue-300 flex items-center gap-2">
                        <x-icon name="info" class="w-4 h-4" />
                        Produk digital akan otomatis aktif untuk download setelah Anda klik "Kirim Produk". Buyer dapat langsung mengunduh file setelah produk dikirim.
                    </p>
                </div>
                
                @if($order->product && $order->product->file_path)
                <div class="glass p-3 rounded-lg mb-4 bg-green-500/10 border border-green-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="check" class="w-6 h-6 text-green-400" />
                            <div>
                                <p class="font-semibold text-sm text-green-400">File produk tersedia</p>
                                <p class="text-xs text-white/60">File siap dikirim ke buyer</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('seller.orders.sendProduct', $order) }}" 
                      x-data="{ sending: false }"
                      @submit.prevent="
                          sending = true;
                          const formData = new FormData($el);
                          fetch('{{ route('seller.orders.sendProduct', $order) }}', {
                              method: 'POST',
                              headers: {
                                  'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                  'Accept': 'application/json'
                              },
                              body: formData
                          })
                          .then(response => response.json())
                          .then(data => {
                              if (data.success || !data.errors) {
                                  window.dispatchEvent(new CustomEvent('toast', { 
                                      detail: { message: data.message || 'Produk berhasil dikirim!', type: 'success' } 
                                  }));
                                  setTimeout(() => window.location.reload(), 1000);
                              } else {
                                  throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Gagal mengirim produk');
                              }
                          })
                          .catch(error => {
                              window.dispatchEvent(new CustomEvent('toast', { 
                                  detail: { message: error.message || 'Gagal mengirim produk. Silakan coba lagi.', type: 'error' } 
                              }));
                              sending = false;
                          });
                      ">
                    @csrf
                    <button type="submit" 
                            :disabled="sending"
                            class="w-full px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold disabled:opacity-50 text-sm cursor-pointer">
                        <span x-show="!sending" class="flex items-center justify-center gap-2">
                            <x-icon name="send" class="w-4 h-4" />
                            Kirim Produk
                        </span>
                        <span x-show="sending" class="flex items-center justify-center">
                            <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengirim...
                        </span>
                    </button>
                </form>
                @else
                <div class="glass p-3 rounded-lg mb-4 bg-yellow-500/20 border border-yellow-500/30">
                    <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                        <x-icon name="alert" class="w-5 h-5" />
                        File Produk Belum Tersedia
                    </p>
                    <p class="text-sm text-yellow-300/80">Anda perlu mengupload file produk terlebih dahulu sebelum dapat mengirim produk ke buyer.</p>
                    <a href="{{ route('seller.products.edit', $order->product) }}" 
                       class="mt-3 inline-block px-4 py-2 bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 rounded-lg text-sm font-semibold transition-colors">
                        <x-icon name="edit" class="w-4 h-4 inline mr-1" />
                        Edit Produk & Upload File
                    </a>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Order Management Controls (Seller) -->
            <x-order-progress-control :order="$order" />
            
            <!-- Upload Deliverable Section (Always visible for service orders) -->
            @php
                $isServiceOrder = $order->type === 'service';
                $isOrderSeller = ($order->service && $order->service->user_id === auth()->id()) || 
                                 ($order->product && $order->product->user_id === auth()->id());
                $canUploadDeliverable = $isServiceOrder && $isOrderSeller && 
                                       in_array($order->status, ['processing', 'waiting_confirmation', 'needs_revision', 'paid']);
            @endphp
            
            @if($canUploadDeliverable)
            <div class="glass p-4 sm:p-6 rounded-lg border-2 border-primary/30 {{ in_array($order->status, ['waiting_confirmation', 'needs_revision']) ? 'bg-orange-500/5 border-orange-500/30' : '' }}">
                <h3 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="package" class="w-6 h-6 text-primary" />
                    Upload Hasil Pekerjaan
                    @if(in_array($order->status, ['waiting_confirmation', 'needs_revision']))
                        <span class="ml-2 px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded border border-orange-500/30 font-semibold">⚠️ Revisi Diperlukan</span>
                    @endif
                </h3>
                
                @if(in_array($order->status, ['waiting_confirmation', 'needs_revision']))
                <div class="mb-4 p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                    <p class="text-sm text-orange-300 flex items-center gap-2">
                        <x-icon name="alert" class="w-4 h-4" />
                        Buyer meminta revisi. Silakan upload hasil pekerjaan yang sudah diperbaiki.
                    </p>
                </div>
                @endif
                
                @if($order->deliverable_path)
                <div class="glass p-3 rounded-lg mb-4 bg-green-500/10 border border-green-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <x-icon name="check" class="w-6 h-6 text-green-400" />
                            <div>
                                <p class="font-semibold text-sm text-green-400">File sudah diupload</p>
                                <p class="text-xs text-white/60">{{ basename($order->deliverable_path) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                           target="_blank"
                           class="px-3 py-1 bg-green-500/20 text-green-400 hover:bg-green-500/30 rounded text-xs font-semibold transition-colors">
                            📥 Download
                        </a>
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('orders.uploadDeliverable', $order) }}" 
                      enctype="multipart/form-data"
                      x-data="{ uploading: false, fileName: '' }"
                      @submit.prevent="
                          uploading = true;
                          const formData = new FormData($el);
                          fetch('{{ route('orders.uploadDeliverable', $order) }}', {
                              method: 'POST',
                              headers: {
                                  'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                  'Accept': 'application/json'
                              },
                              body: formData
                          })
                          .then(response => response.json())
                          .then(data => {
                              if (data.success || !data.errors) {
                                  window.dispatchEvent(new CustomEvent('toast', { 
                                      detail: { message: data.message || 'File berhasil diupload!', type: 'success' } 
                                  }));
                                  setTimeout(() => window.location.reload(), 1000);
                              } else {
                                  throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Upload gagal');
                              }
                          })
                          .catch(error => {
                              window.dispatchEvent(new CustomEvent('toast', { 
                                  detail: { message: error.message || 'Upload gagal. Silakan coba lagi.', type: 'error' } 
                              }));
                              uploading = false;
                          });
                      ">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-2">File Hasil Pekerjaan *</label>
                            <input type="file" 
                                   name="deliverable" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt"
                                   @change="fileName = $event.target.files[0]?.name || ''"
                                   required
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm cursor-pointer"
                                   :disabled="uploading">
                            <p class="text-xs text-white/60 mt-1">
                                Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, TXT (Max 10MB)
                            </p>
                            <p x-show="fileName" class="text-xs text-primary mt-1" x-text="'File: ' + fileName"></p>
                            @if($order->deliverable_path)
                            <p class="text-xs text-yellow-400 mt-2">
                                <span class="flex items-center gap-1">
                                    <x-icon name="alert" class="w-3 h-3" />
                                    File hasil pekerjaan sudah ada. Upload file baru akan mengganti file yang lama.
                                </span>
                            </p>
                            @endif
                        </div>
                        
                        <button type="submit" 
                                :disabled="uploading"
                                class="w-full px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold disabled:opacity-50 text-sm cursor-pointer">
                            <span x-show="!uploading" class="flex items-center justify-center gap-2">
                                @if($order->deliverable_path)
                                    <x-icon name="refresh" class="w-4 h-4" />
                                    🔄 Update Hasil Pekerjaan
                                @else
                                    <x-icon name="upload" class="w-4 h-4" />
                                    📤 Upload Hasil Pekerjaan
                                @endif
                            </span>
                            <span x-show="uploading" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            @endif
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

