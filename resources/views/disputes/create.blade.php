@extends('layouts.app')

@section('title', 'Buat Dispute - Pesanan #' . $order->order_number . ' - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-2 text-primary hover:underline mb-4">
            <x-icon name="arrow-left" class="w-5 h-5" />
            <span>Kembali ke Detail Pesanan</span>
        </a>
        
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Buat Dispute</h1>
        <p class="text-white/70">Pesanan #{{ $order->order_number }}</p>
    </div>

    <!-- Info Box -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-6 border border-orange-500/30 bg-orange-500/10">
        <div class="flex items-start gap-3">
            <x-icon name="alert" class="w-6 h-6 text-orange-400 flex-shrink-0 mt-0.5" />
            <div>
                <h3 class="font-semibold text-orange-400 mb-2">Tentang Dispute</h3>
                <p class="text-sm text-white/80 leading-relaxed">
                    Dispute akan membekukan dana di escrow sampai admin menyelesaikannya. 
                    Admin akan meninjau dispute Anda dan memutuskan apakah dana akan dilepas ke seller atau dikembalikan ke Anda.
                    <strong class="text-orange-300">Pastikan Anda memberikan informasi yang jelas dan lengkap.</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Dispute Form -->
    <form action="{{ route('disputes.store', $order) }}" method="POST" enctype="multipart/form-data" class="glass p-4 sm:p-6 rounded-xl border border-white/10">
        @csrf
        
        <!-- Category Selection -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-3">
                Kategori Dispute <span class="text-red-400">*</span>
            </label>
            <div class="space-y-2">
                <label class="flex items-start gap-3 p-4 rounded-lg border border-white/20 hover:border-primary/50 cursor-pointer transition-all">
                    <input type="radio" name="category" value="product_not_as_described" required class="mt-1">
                    <div>
                        <div class="font-medium">Produk tidak sesuai deskripsi</div>
                        <div class="text-sm text-white/60">Produk yang diterima berbeda dari yang dijelaskan</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-4 rounded-lg border border-white/20 hover:border-primary/50 cursor-pointer transition-all">
                    <input type="radio" name="category" value="quality_issue" required class="mt-1">
                    <div>
                        <div class="font-medium">Kualitas produk/jasa tidak sesuai</div>
                        <div class="text-sm text-white/60">Kualitas produk atau hasil jasa tidak memenuhi standar</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-4 rounded-lg border border-white/20 hover:border-primary/50 cursor-pointer transition-all">
                    <input type="radio" name="category" value="seller_unresponsive" required class="mt-1">
                    <div>
                        <div class="font-medium">Seller tidak responsif</div>
                        <div class="text-sm text-white/60">Seller tidak merespon komunikasi atau permintaan revisi</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-4 rounded-lg border border-white/20 hover:border-primary/50 cursor-pointer transition-all">
                    <input type="radio" name="category" value="delivery_issue" required class="mt-1">
                    <div>
                        <div class="font-medium">Masalah pengiriman</div>
                        <div class="text-sm text-white/60">Masalah dengan pengiriman produk atau deliverable</div>
                    </div>
                </label>
                
                <label class="flex items-start gap-3 p-4 rounded-lg border border-white/20 hover:border-primary/50 cursor-pointer transition-all">
                    <input type="radio" name="category" value="other" required class="mt-1">
                    <div>
                        <div class="font-medium">Lainnya</div>
                        <div class="text-sm text-white/60">Alasan lain yang tidak termasuk kategori di atas</div>
                    </div>
                </label>
            </div>
            @error('category')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Reason -->
        <div class="mb-6">
            <label for="reason" class="block text-sm font-semibold mb-2">
                Alasan Dispute <span class="text-red-400">*</span>
                <span class="text-xs text-white/60 font-normal">(Minimal 50 karakter)</span>
            </label>
            <textarea 
                name="reason" 
                id="reason" 
                rows="6" 
                required 
                minlength="50"
                maxlength="1000"
                placeholder="Jelaskan secara detail alasan Anda membuat dispute. Semakin detail, semakin mudah admin meninjau dan menyelesaikan dispute ini..."
                class="w-full px-4 py-3 glass border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"
            >{{ old('reason') }}</textarea>
            <div class="flex items-center justify-between mt-2">
                <p class="text-xs text-white/60">Jelaskan masalah dengan detail dan jelas</p>
                <span id="char-count" class="text-xs text-white/60">0 / 1000 karakter</span>
            </div>
            @error('reason')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Attachments -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">
                Bukti Pendukung (Opsional)
                <span class="text-xs text-white/60 font-normal">(Maksimal 5 file, format: JPG, PNG, PDF, maks 5MB per file)</span>
            </label>
            <input 
                type="file" 
                name="attachments[]" 
                id="attachments" 
                multiple 
                accept="image/jpeg,image/png,application/pdf"
                class="hidden"
            >
            <div class="flex flex-col gap-3">
                <button 
                    type="button" 
                    onclick="document.getElementById('attachments').click()"
                    class="px-4 py-3 glass border border-white/20 rounded-lg hover:border-primary/50 transition-all text-left flex items-center gap-2"
                >
                    <x-icon name="file-text" class="w-5 h-5" />
                    <span>Pilih File Bukti</span>
                </button>
                <div id="file-list" class="space-y-2"></div>
            </div>
            @error('attachments')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
            @error('attachments.*')
                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Escrow Info -->
        <div class="mb-6 p-4 rounded-lg bg-blue-500/10 border border-blue-500/30">
            <div class="flex items-center gap-2 mb-2">
                <x-icon name="info" class="w-5 h-5 text-blue-400" />
                <span class="font-semibold text-blue-400">Informasi Escrow</span>
            </div>
            <div class="text-sm space-y-1 text-white/80">
                <div>Total Escrow: <strong>Rp {{ number_format($order->escrow->amount, 0, ',', '.') }}</strong></div>
                <div>Status: <strong>Dana Ditahan</strong></div>
                <div>Dana akan dibekukan sampai admin menyelesaikan dispute ini.</div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a 
                href="{{ route('orders.show', $order) }}" 
                class="flex-1 px-6 py-3 glass border border-white/20 rounded-lg text-center hover:border-white/40 transition-all"
            >
                Batal
            </a>
            <button 
                type="submit" 
                class="flex-1 px-6 py-3 bg-orange-500 hover:bg-orange-600 rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-orange-500/20 flex items-center justify-center gap-2"
            >
                <x-icon name="alert" class="w-5 h-5" />
                Buat Dispute
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter
    const reasonTextarea = document.getElementById('reason');
    const charCount = document.getElementById('char-count');
    
    reasonTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length} / 1000 karakter`;
        
        if (length < 50) {
            charCount.classList.add('text-red-400');
            charCount.classList.remove('text-white/60');
        } else {
            charCount.classList.remove('text-red-400');
            charCount.classList.add('text-white/60');
        }
    });
    
    // File preview
    const fileInput = document.getElementById('attachments');
    const fileList = document.getElementById('file-list');
    
    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        
        if (this.files.length > 5) {
            alert('Maksimal 5 file');
            this.value = '';
            return;
        }
        
        Array.from(this.files).forEach((file, index) => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`File "${file.name}" terlalu besar. Maksimal 5MB`);
                return;
            }
            
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-3 glass rounded-lg border border-white/10';
            fileItem.innerHTML = `
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    <x-icon name="file-text" class="w-5 h-5 flex-shrink-0" />
                    <span class="text-sm truncate">${file.name}</span>
                    <span class="text-xs text-white/60">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-400 hover:text-red-500">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    });
    
    window.removeFile = function(index) {
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        files.splice(index, 1);
        files.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change'));
    };
});
</script>
@endpush
@endsection

