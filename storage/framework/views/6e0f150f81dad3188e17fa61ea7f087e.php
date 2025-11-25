<?php $__env->startSection('title', 'Pembelian Kuota XL - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6 sm:mb-8">
        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'smartphone','class' => 'w-6 h-6 sm:w-8 sm:h-8 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'smartphone','class' => 'w-6 h-6 sm:w-8 sm:h-8 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Pembelian Kuota XL</h1>
            <p class="text-white/60 text-sm sm:text-base">Beli paket kuota XL dengan mudah dan cepat</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Purchase Form & History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Purchase Form -->
            <div class="glass p-4 sm:p-6 rounded-lg border border-white/10" x-data="quotaForm()">
                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Form Pembelian</h2>

                <form method="POST" action="<?php echo e(route('quota.purchase')); ?>" x-on:submit.prevent="handleSubmit">
                    <?php echo csrf_field(); ?>

                    <!-- Provider & Product Selection -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <!-- Provider -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">
                                Provider <span class="text-red-400">*</span>
                            </label>
                            <select name="provider" 
                                    x-model="formData.provider"
                                    x-on:change="onProviderChange"
                                    class="w-full glass border border-white/20 rounded-lg px-4 py-3 bg-white/5 text-white text-sm sm:text-base focus:outline-none focus:border-primary"
                                    style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E\"), rgba(14, 14, 16, 0.98) !important;">
                                <option value="" style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;">Pilih Provider</option>
                                <template x-for="(products, providerName) in productsData" :key="providerName">
                                    <option :value="providerName" 
                                            x-text="providerName" 
                                            :style="'background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;'"
                                            style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;"></option>
                                </template>
                            </select>
                            <?php $__errorArgs = ['provider'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Product -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">
                                Produk <span class="text-red-400">*</span>
                            </label>
                            <select name="produk" 
                                    x-model="formData.produk"
                                    :disabled="!formData.provider"
                                    class="w-full glass border border-white/20 rounded-lg px-4 py-3 bg-white/5 text-white text-sm sm:text-base focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed"
                                    style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important; background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E\"), rgba(14, 14, 16, 0.98) !important;">
                                <option value="" style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;">Pilih Produk</option>
                                <template x-if="formData.provider && productsData[formData.provider]">
                                    <template x-for="product in productsData[formData.provider]" :key="product.kode">
                                        <option :value="product.kode" 
                                                x-text="product.nama + ' - Rp ' + formatPrice(product.harga)" 
                                                :style="'background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;'"
                                                style="background-color: rgba(14, 14, 16, 0.98) !important; color: white !important;"></option>
                                    </template>
                                </template>
                            </select>
                            <?php $__errorArgs = ['produk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">
                            Nomor Tujuan <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               name="tujuan" 
                               x-model="formData.tujuan"
                               placeholder="08xxxxxxxxxx"
                               pattern="[0-9]{10,13}"
                               maxlength="13"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 text-white placeholder-white/40 text-sm sm:text-base focus:outline-none focus:border-primary focus:bg-white/10"
                               required>
                        <?php $__errorArgs = ['tujuan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Multi Transaction -->
                    <div class="mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                   name="multi_transaksi" 
                                   x-model="formData.multiTransaksi"
                                   value="1"
                                   class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary">
                            <span class="text-sm text-white/80">Aktifkan Multi Transaksi</span>
                        </label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" 
                                :disabled="!canSubmit || loading"
                                class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-white">
                            <template x-if="!loading">
                                <span>Beli Sekarang</span>
                            </template>
                            <template x-if="loading">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Memproses...</span>
                                </span>
                            </template>
                        </button>

                        <!-- Check Stock Buttons -->
                        <button type="button" 
                                x-on:click="checkStock('XLA')"
                                :disabled="loading || stockLoading"
                                class="px-4 sm:px-6 py-3 glass glass-hover rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-white">
                            <template x-if="!stockLoading || currentStockType !== 'XLA'">
                                <span>Cek Stok XLA</span>
                            </template>
                            <template x-if="stockLoading && currentStockType === 'XLA'">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Loading...</span>
                                </span>
                            </template>
                        </button>

                        <button type="button" 
                                x-on:click="checkStock('XDA')"
                                :disabled="loading || stockLoading"
                                class="px-4 sm:px-6 py-3 glass glass-hover rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-white">
                            <template x-if="!stockLoading || currentStockType !== 'XDA'">
                                <span>Cek Stok XDA</span>
                            </template>
                            <template x-if="stockLoading && currentStockType === 'XDA'">
                                <span class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Loading...</span>
                                </span>
                            </template>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Transaction History -->
            <div class="glass p-4 sm:p-6 rounded-lg border border-white/10">
                <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Riwayat Transaksi</h2>
                <?php if($transactions->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">TrxID</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Waktu</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Kode</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Tujuan</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Harga</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Saldo Awal</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Status</th>
                                    <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm font-semibold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-4 text-xs sm:text-sm font-mono text-white/80">
                                        <?php echo e($transaction->trx_id ?? '-'); ?>

                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/80">
                                        <?php echo e($transaction->created_at->format('d M Y H:i')); ?>

                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-mono">
                                        <?php echo e($transaction->produk); ?>

                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-mono">
                                        <?php echo e($transaction->tujuan); ?>

                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/80 font-semibold">
                                        Rp <?php echo e(number_format($transaction->harga, 0, ',', '.')); ?>

                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/80">
                                        Rp <?php echo e(number_format($transaction->saldo_awal, 0, ',', '.')); ?>

                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-2">
                                            <?php
                                                $statusColor = match($transaction->status) {
                                                    'success' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                                    'failed' => 'bg-red-500/20 text-red-400 border-red-500/50',
                                                    'processing' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                                    default => 'bg-gray-500/20 text-gray-400 border-gray-500/50',
                                                };
                                            ?>
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold border <?php echo e($statusColor); ?>">
                                                <?php echo e($transaction->status_display ?? ucfirst($transaction->status)); ?>

                                            </span>
                                            
                                            <?php if($transaction->status === 'failed' && $transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal): ?>
                                                <form id="refund-failed-form-<?php echo e($transaction->ref_id); ?>" method="POST" action="<?php echo e(route('quota.refund', $transaction->ref_id)); ?>" style="display: none;">
                                                    <?php echo csrf_field(); ?>
                                                </form>
                                                <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'refund-failed-modal-'.e($transaction->ref_id).'','title' => 'Refund Transaksi','message' => 'Yakin ingin refund transaksi ini? Saldo sebesar Rp '.e(number_format($transaction->harga, 0, ',', '.')).' akan dikembalikan ke wallet Anda.','confirmText' => 'Ya, Refund','cancelText' => 'Tidak','type' => 'info','formId' => 'refund-failed-form-' . $transaction->ref_id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'refund-failed-modal-'.e($transaction->ref_id).'','title' => 'Refund Transaksi','message' => 'Yakin ingin refund transaksi ini? Saldo sebesar Rp '.e(number_format($transaction->harga, 0, ',', '.')).' akan dikembalikan ke wallet Anda.','confirm-text' => 'Ya, Refund','cancel-text' => 'Tidak','type' => 'info','form-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('refund-failed-form-' . $transaction->ref_id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $attributes = $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10)): ?>
<?php $component = $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10; ?>
<?php unset($__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10); ?>
<?php endif; ?>
                                                <button type="button" 
                                                        onclick="document.getElementById('refund-failed-modal-<?php echo e($transaction->ref_id); ?>').style.display = 'flex'; document.body.style.overflow = 'hidden';"
                                                        class="px-3 py-1 text-xs glass glass-hover rounded border border-blue-500/50 text-blue-400 hover:bg-blue-500/20 transition-colors">
                                                    Refund
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-xs sm:text-sm text-white/60 max-w-xs truncate" title="<?php echo e($transaction->keterangan ?? ''); ?>">
                                        <?php echo e($transaction->keterangan ?? '-'); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-16 h-16 mx-auto mb-4 text-white/20']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-16 h-16 mx-auto mb-4 text-white/20']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                        <p class="text-white/60 text-lg mb-2">Belum ada data.</p>
                        <p class="text-white/40 text-sm">Transaksi Anda akan muncul di sini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column: Info -->
        <div class="lg:col-span-1">
            <div class="glass p-4 sm:p-6 rounded-lg border border-white/10 sticky top-6">
                <div class="flex items-center gap-2 mb-4">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'w-5 h-5 text-primary']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                    <h3 class="text-lg font-bold">Informasi</h3>
                </div>
                <div class="space-y-3 text-sm text-white/80">
                    <p>• Pastikan nomor tujuan aktif dan dapat menerima kuota</p>
                    <p>• Transaksi akan diproses dalam waktu 1-5 menit</p>
                    <p>• Saldo akan dikurangi setelah transaksi berhasil</p>
                    <p>• Jika terjadi masalah, hubungi admin</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stock Modal -->
<div x-data="{ show: false, stockData: [], stockType: '' }" 
     x-show="show" 
     x-on:show-stock-modal.window="show = true; stockData = $event.detail.data; stockType = $event.detail.type"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
     style="display: none;">
    <div class="glass p-6 rounded-lg border border-white/10 max-w-2xl w-full max-h-[90vh] overflow-y-auto"
         x-on:click.away="show = false">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold" x-text="'Stok ' + stockType"></h3>
            <button x-on:click="show = false" class="text-white/60 hover:text-white">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-6 h-6']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
            </button>
        </div>
        
        <div class="space-y-2">
            <template x-if="stockData && stockData.length > 0">
                <template x-for="item in stockData" :key="item.type || item.kode">
                    <div class="glass p-3 rounded-lg border border-white/10">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold" x-text="item.nama || item.name || 'N/A'"></p>
                                <p class="text-sm text-white/60" x-text="item.type || item.kode || 'N/A'"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-white/60">Sisa Slot</p>
                                <p class="text-lg font-bold" x-text="item.sisa_slot || item.stock || 0"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <template x-if="!stockData || stockData.length === 0">
                <p class="text-white/60 text-center py-4">Tidak ada data stok tersedia</p>
            </template>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button @click="show = false" 
                    class="px-6 py-2 glass glass-hover rounded-lg font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function quotaForm() {
    return {
        productsData: <?php echo json_encode($products ?? []); ?>,
        formData: {
            provider: '',
            produk: '',
            tujuan: '',
            multiTransaksi: false
        },
        loading: false,
        stockLoading: false,
        currentStockType: '',
        
        init() {
            console.log('Products loaded:', this.productsData);
            console.log('Providers:', Object.keys(this.productsData || {}));
            
            if (!this.productsData || typeof this.productsData !== 'object' || Array.isArray(this.productsData)) {
                console.warn('Invalid products data format, setting to empty object');
                this.productsData = {};
            }
        },
        
        get canSubmit() {
            return this.formData.provider && 
                   this.formData.produk && 
                   this.formData.tujuan &&
                   this.formData.tujuan.length >= 10;
        },
        
        onProviderChange() {
            this.formData.produk = '';
        },
        
        formatPrice(price) {
            return new Intl.NumberFormat('id-ID').format(price || 0);
        },
        
        handleSubmit(event) {
            if (!this.canSubmit) {
                event.preventDefault();
                return false;
            }
            this.loading = true;
            event.target.submit();
        },
        
        async checkStock(type) {
            if (this.stockLoading) return;
            this.stockLoading = true;
            this.currentStockType = type;
            
            try {
                const formData = new FormData();
                formData.append('type', type);
                formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                
                const response = await fetch('<?php echo e(route('quota.checkStock')); ?>', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success && data.data) {
                    const stockData = data.data.stock || data.data.data || (Array.isArray(data.data) ? data.data : []);
                    if (stockData && stockData.length > 0) {
                        window.dispatchEvent(new CustomEvent('show-stock-modal', {
                            detail: { data: stockData, type: type }
                        }));
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { message: 'Data stock kosong', type: 'warning' }
                        }));
                    }
                } else {
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: { message: data.message || 'Gagal mengambil data stock', type: 'error' }
                    }));
                }
            } catch (error) {
                console.error('Check stock error:', error);
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: 'Gagal cek stock. Silakan coba lagi.', type: 'error' }
                }));
            } finally {
                this.stockLoading = false;
                this.currentStockType = '';
            }
        }
    }
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/quota/index.blade.php ENDPATH**/ ?>