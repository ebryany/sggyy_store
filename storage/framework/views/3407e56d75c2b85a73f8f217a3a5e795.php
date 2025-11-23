<?php $__env->startSection('title', 'Kelola Pembayaran - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'w-8 h-8 sm:w-10 sm:h-10']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-8 h-8 sm:w-10 sm:h-10']); ?>
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
                Kelola Pembayaran
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola dan verifikasi transaksi pembayaran</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
    
    <!-- Filter -->
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="<?php echo e(route('admin.payments.index')); ?>" class="flex flex-wrap items-center gap-3 sm:gap-4">
            <select name="status" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <option value="all" <?php echo e($status === 'all' ? 'selected' : ''); ?>>Semua Status</option>
                <option value="pending" <?php echo e($status === 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                <option value="verified" <?php echo e($status === 'verified' ? 'selected' : ''); ?>>Terverifikasi</option>
                <option value="rejected" <?php echo e($status === 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                Filter
            </button>
        </form>
    </div>
    
    <!-- Payments Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <h2 class="text-xl sm:text-2xl font-semibold mb-4">Daftar Transaksi Pembayaran</h2>
        
        <?php if($payments->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">ID Pesanan</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Pembeli</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Produk/Layanan</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Jumlah</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Metode</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Bukti</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-xs sm:text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4">
                            <a href="<?php echo e(route('orders.show', $payment->order)); ?>" 
                               class="font-mono text-sm text-primary hover:underline" target="_blank">
                                #<?php echo e($payment->order->id); ?>

                            </a>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <img src="<?php echo e($payment->order->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($payment->order->user->name)); ?>" 
                                     alt="<?php echo e($payment->order->user->name); ?>" 
                                     class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-sm font-semibold"><?php echo e($payment->order->user->name); ?></p>
                                    <p class="text-xs text-white/60"><?php echo e($payment->order->user->email); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-sm">
                                <?php if($payment->order->product): ?>
                                    <p class="font-semibold truncate max-w-[150px]"><?php echo e($payment->order->product->title); ?></p>
                                    <p class="text-white/60 text-xs">Produk</p>
                                <?php elseif($payment->order->service): ?>
                                    <p class="font-semibold truncate max-w-[150px]"><?php echo e($payment->order->service->title); ?></p>
                                    <p class="text-white/60 text-xs">Layanan</p>
                                <?php else: ?>
                                    <p class="text-white/60 text-xs">-</p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-base sm:text-lg font-bold text-primary">Rp <?php echo e(number_format($payment->order->total, 0, ',', '.')); ?></span>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <div class="flex items-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => ''.e($payment->getMethodIcon()).'','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($payment->getMethodIcon()).'','class' => 'w-4 h-4']); ?>
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
                                <?php echo e($payment->getMethodDisplayName()); ?>

                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($payment->proof_path): ?>
                            <button type="button"
                                    onclick="
                                        const modal = document.getElementById('proof-modal-<?php echo e($payment->id); ?>');
                                        if (modal) {
                                            modal.style.display = 'flex';
                                            document.body.style.overflow = 'hidden';
                                        }
                                    "
                                    class="text-primary hover:underline text-xs sm:text-sm font-semibold transition-colors cursor-pointer flex items-center gap-1">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'camera','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'camera','class' => 'w-4 h-4']); ?>
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
                                Lihat Bukti
                            </button>
                            <?php else: ?>
                            <span class="text-white/40 text-xs sm:text-sm">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($payment->status === 'pending'): ?>
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Menunggu</span>
                            <?php elseif($payment->status === 'verified'): ?>
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Terverifikasi</span>
                            <?php else: ?>
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs">Ditolak</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-white/60 text-xs sm:text-sm">
                            <?php echo e($payment->created_at->format('d M Y, H:i')); ?>

                        </td>
                        <td class="py-3 px-4">
                            <?php if($payment->status === 'pending'): ?>
                            <div class="flex flex-col gap-2">
                                <button type="button"
                                        @click="
                                            const modal = document.getElementById('detail-modal-<?php echo e($payment->id); ?>');
                                            if (modal) {
                                                modal.style.display = 'flex';
                                                document.body.style.overflow = 'hidden';
                                            }
                                        "
                                        class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded text-xs hover:bg-blue-500/30 whitespace-nowrap transition-colors font-semibold cursor-pointer flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'eye','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'w-4 h-4']); ?>
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
                                    Lihat Detail
                                </button>
                                <div class="flex gap-1">
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('verify-payment-modal-<?php echo e($payment->id); ?>');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            "
                                            class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs hover:bg-green-500/30 whitespace-nowrap cursor-pointer flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4']); ?>
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
                                        Verifikasi
                                    </button>
                                    
                                    <!-- Verify Payment Modal -->
                                    <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'verify-payment-modal-'.e($payment->id).'','title' => 'Verifikasi Pembayaran','message' => 'Yakin verifikasi pembayaran ini? Pastikan sudah cek bukti pembayaran dan detail user!','confirmText' => 'Ya, Verifikasi','cancelText' => 'Batal','type' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'verify-payment-modal-'.e($payment->id).'','title' => 'Verifikasi Pembayaran','message' => 'Yakin verifikasi pembayaran ini? Pastikan sudah cek bukti pembayaran dan detail user!','confirm-text' => 'Ya, Verifikasi','cancel-text' => 'Batal','type' => 'info']); ?>
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
                                    
                                    <form id="verify-payment-form-<?php echo e($payment->id); ?>" method="POST" action="<?php echo e(route('admin.payments.verify', $payment)); ?>" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const verifyBtn<?php echo e($payment->id); ?> = document.getElementById('verify-payment-modal-<?php echo e($payment->id); ?>-confirm-btn');
                                            const verifyModal<?php echo e($payment->id); ?> = document.getElementById('verify-payment-modal-<?php echo e($payment->id); ?>');
                                            const verifyForm<?php echo e($payment->id); ?> = document.getElementById('verify-payment-form-<?php echo e($payment->id); ?>');
                                            
                                            if (verifyBtn<?php echo e($payment->id); ?> && verifyModal<?php echo e($payment->id); ?> && verifyForm<?php echo e($payment->id); ?>) {
                                                verifyBtn<?php echo e($payment->id); ?>.addEventListener('click', function() {
                                                    verifyModal<?php echo e($payment->id); ?>.style.display = 'none';
                                                    document.body.style.overflow = '';
                                                    verifyForm<?php echo e($payment->id); ?>.submit();
                                                });
                                            }
                                        });
                                    </script>
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('reject-modal-<?php echo e($payment->id); ?>');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            "
                                            class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs hover:bg-red-500/30 whitespace-nowrap cursor-pointer flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4']); ?>
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
                                        Tolak
                                    </button>
                                </div>
                            </div>
                            <?php elseif($payment->verifier): ?>
                            <p class="text-xs text-white/60">
                                Oleh: <?php echo e($payment->verifier->name); ?><br>
                                <?php if($payment->verified_at): ?>
                                <?php echo e($payment->verified_at->format('d M Y, H:i')); ?>

                                <?php endif; ?>
                            </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <!-- Detail Modal (untuk lihat semua info sebelum verify) -->
                    <?php if($payment->status === 'pending'): ?>
                    <div id="detail-modal-<?php echo e($payment->id); ?>"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                             onclick="event.stopPropagation()">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <h3 class="text-xl sm:text-2xl font-semibold flex items-center gap-2">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'document','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'document','class' => 'w-6 h-6']); ?>
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
                                    Detail Pembayaran & Data Pembeli
                                </h3>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('detail-modal-<?php echo e($payment->id); ?>');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="text-white/60 hover:text-white transition-colors p-1 hover:bg-white/10 rounded cursor-pointer">
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
                            
                            <div class="space-y-4">
                                <!-- User Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary">👤 Informasi User</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-white/60">Nama</p>
                                            <p class="font-semibold"><?php echo e($payment->order->user->name); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">Email</p>
                                            <p class="font-semibold"><?php echo e($payment->order->user->email); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">User ID</p>
                                            <p class="font-semibold">#<?php echo e($payment->order->user->id); ?></p>
                                        </div>
                                        <div>
                                            <p class="text-white/60">Role</p>
                                            <p class="font-semibold"><?php echo e(ucfirst($payment->order->user->role)); ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary">📦 Informasi Pesanan</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Nomor Pesanan</span>
                                            <span class="font-semibold"><?php echo e($payment->order->order_number); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Jenis</span>
                                            <span class="font-semibold"><?php echo e($payment->order->type === 'product' ? 'Produk' : 'Layanan'); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Item</span>
                                            <span class="font-semibold">
                                                <?php if($payment->order->product): ?>
                                                    <?php echo e($payment->order->product->title); ?>

                                                <?php elseif($payment->order->service): ?>
                                                    <?php echo e($payment->order->service->title); ?>

                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Total</span>
                                            <span class="font-semibold text-primary text-lg">Rp <?php echo e(number_format($payment->order->total, 0, ',', '.')); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Status Pesanan</span>
                                            <span class="font-semibold">
                                                <?php if($payment->order->status === 'pending'): ?> Menunggu
                                                <?php elseif($payment->order->status === 'paid'): ?> Dibayar
                                                <?php elseif($payment->order->status === 'processing'): ?> Diproses
                                                <?php elseif($payment->order->status === 'completed'): ?> Selesai
                                                <?php elseif($payment->order->status === 'cancelled'): ?> Dibatalkan
                                                <?php else: ?> <?php echo e(ucfirst($payment->order->status)); ?>

                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <?php if($payment->order->notes): ?>
                                        <div>
                                            <p class="text-white/60 mb-1">Catatan</p>
                                            <p class="font-semibold"><?php echo e($payment->order->notes); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Payment Info -->
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary flex items-center gap-2">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'credit-card','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-5 h-5']); ?>
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
                                        Informasi Pembayaran
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-white/60">ID Pembayaran</span>
                                            <span class="font-semibold">#<?php echo e($payment->id); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Metode</span>
                                            <span class="font-semibold"><?php echo e($payment->getMethodDisplayName()); ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Status</span>
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">Menunggu</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-white/60">Tanggal Pembayaran</span>
                                            <span class="font-semibold"><?php echo e($payment->created_at->format('d M Y, H:i')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Proof -->
                                <?php if($payment->proof_path): ?>
                                <div class="glass p-4 rounded-lg">
                                    <h4 class="font-semibold mb-3 text-primary flex items-center gap-2">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'camera','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'camera','class' => 'w-5 h-5']); ?>
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
                                        Bukti Pembayaran
                                    </h4>
                                    <?php
                                        $proofUrl = $payment->getProofUrl();
                                    ?>
                                    
                                    <?php if($payment->isProofImage()): ?>
                                    <div class="mb-3">
                                        <img src="<?php echo e($proofUrl); ?>" 
                                             alt="Payment Proof" 
                                             class="w-full rounded-lg border-2 border-white/10 cursor-pointer hover:opacity-90 transition-opacity"
                                             onclick="window.open('<?php echo e($proofUrl); ?>', '_blank')"
                                             title="Klik untuk membuka di tab baru">
                                    </div>
                                    <a href="<?php echo e($proofUrl); ?>" 
                                       target="_blank" 
                                       class="text-primary hover:underline text-sm flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-4 h-4']); ?>
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
                                        Buka di tab baru
                                    </a>
                                    <?php elseif($payment->isProofPdf()): ?>
                                    <div class="mb-3 p-3 bg-white/5 rounded-lg">
                                        <div class="flex items-center gap-3 mb-3">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-8 h-8 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-8 h-8 text-primary']); ?>
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
                                            <div class="flex-1">
                                                <p class="font-semibold">File PDF</p>
                                                <p class="text-xs text-white/60"><?php echo e(basename($payment->proof_path)); ?></p>
                                            </div>
                                        </div>
                                        <iframe src="<?php echo e($proofUrl); ?>" 
                                                class="w-full h-64 rounded-lg border-2 border-white/10"
                                                frameborder="0">
                                        </iframe>
                                    </div>
                                    <a href="<?php echo e($proofUrl); ?>" 
                                       target="_blank" 
                                       class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center text-sm font-semibold transition-colors">
                                        📥 Download / Buka PDF
                                    </a>
                                    <?php else: ?>
                                    <div class="mb-3">
                                        <a href="<?php echo e($proofUrl); ?>" 
                                           target="_blank" 
                                           class="flex items-center gap-3 p-3 bg-primary/20 hover:bg-primary/30 rounded-lg transition-colors">
                                            <span class="text-2xl">📎</span>
                                            <div class="flex-1">
                                                <p class="font-semibold">Bukti Pembayaran</p>
                                                <p class="text-xs text-white/60"><?php echo e(basename($payment->proof_path)); ?></p>
                                            </div>
                                            <span class="text-primary">→</span>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php else: ?>
                                <div class="glass p-4 rounded-lg border-2 border-yellow-500/30">
                                    <p class="text-yellow-400 font-semibold">⚠️ Belum ada bukti pembayaran yang diupload</p>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Actions -->
                                <div class="flex gap-3 pt-4 border-t border-white/10">
                                    <a href="<?php echo e(route('orders.show', $payment->order)); ?>" 
                                       target="_blank"
                                       class="flex-1 px-4 py-2 glass glass-hover rounded-lg text-center flex items-center justify-center gap-2">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'document','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'document','class' => 'w-4 h-4']); ?>
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
                                        Lihat Detail Pesanan
                                    </a>
                                    <?php if($payment->proof_path): ?>
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('verify-payment-simple-modal-<?php echo e($payment->id); ?>');
                                                if (modal) {
                                                    modal.style.display = 'flex';
                                                    document.body.style.overflow = 'hidden';
                                                }
                                            "
                                            class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg transition-colors font-semibold cursor-pointer flex items-center justify-center gap-2">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-5 h-5']); ?>
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
                                        Verifikasi Pembayaran
                                    </button>
                                    
                                    <!-- Verify Payment Simple Modal -->
                                    <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'verify-payment-simple-modal-'.e($payment->id).'','title' => 'Verifikasi Pembayaran','message' => 'Yakin verifikasi pembayaran ini?','confirmText' => 'Ya, Verifikasi','cancelText' => 'Batal','type' => 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'verify-payment-simple-modal-'.e($payment->id).'','title' => 'Verifikasi Pembayaran','message' => 'Yakin verifikasi pembayaran ini?','confirm-text' => 'Ya, Verifikasi','cancel-text' => 'Batal','type' => 'info']); ?>
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
                                    
                                    <form id="verify-payment-simple-form-<?php echo e($payment->id); ?>" method="POST" action="<?php echo e(route('admin.payments.verify', $payment)); ?>" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const verifyBtn<?php echo e($payment->id); ?> = document.getElementById('verify-payment-simple-modal-<?php echo e($payment->id); ?>-confirm-btn');
                                            const verifyModal<?php echo e($payment->id); ?> = document.getElementById('verify-payment-simple-modal-<?php echo e($payment->id); ?>');
                                            const verifyForm<?php echo e($payment->id); ?> = document.getElementById('verify-payment-simple-form-<?php echo e($payment->id); ?>');
                                            
                                            if (verifyBtn<?php echo e($payment->id); ?> && verifyModal<?php echo e($payment->id); ?> && verifyForm<?php echo e($payment->id); ?>) {
                                                verifyBtn<?php echo e($payment->id); ?>.addEventListener('click', function() {
                                                    verifyModal<?php echo e($payment->id); ?>.style.display = 'none';
                                                    document.body.style.overflow = '';
                                                    verifyForm<?php echo e($payment->id); ?>.submit();
                                                });
                                            }
                                        });
                                    </script>
                                    <?php endif; ?>
                                    <button type="button"
                                            onclick="
                                                const detailModal = document.getElementById('detail-modal-<?php echo e($payment->id); ?>');
                                                if (detailModal) {
                                                    detailModal.style.display = 'none';
                                                }
                                                document.body.style.overflow = '';
                                                setTimeout(() => {
                                                    const rejectModal = document.getElementById('reject-modal-<?php echo e($payment->id); ?>');
                                                    if (rejectModal) {
                                                        rejectModal.style.display = 'flex';
                                                        document.body.style.overflow = 'hidden';
                                                    }
                                                }, 100);
                                            "
                                            class="flex-1 px-4 py-2 bg-red-500/20 text-red-400 hover:bg-red-500/30 rounded-lg transition-colors font-semibold cursor-pointer flex items-center justify-center gap-2">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5']); ?>
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
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Proof Modal (Standalone) -->
                    <?php if($payment->proof_path): ?>
                    <div id="proof-modal-<?php echo e($payment->id); ?>"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                             onclick="event.stopPropagation()">
                            <div class="flex justify-between items-center mb-4 pb-4 border-b border-white/10">
                                <h3 class="text-xl font-semibold flex items-center gap-2">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'camera','class' => 'w-6 h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'camera','class' => 'w-6 h-6']); ?>
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
                                    Bukti Pembayaran - ID #<?php echo e($payment->id); ?>

                                </h3>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('proof-modal-<?php echo e($payment->id); ?>');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="text-white/60 hover:text-white transition-colors p-1 hover:bg-white/10 rounded cursor-pointer">
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
                            <div class="mb-4">
                                <p class="text-white/60 text-sm mb-2">
                                    Pesanan: <a href="<?php echo e(route('orders.show', $payment->order)); ?>" target="_blank" class="text-primary hover:underline"><?php echo e($payment->order->order_number); ?></a> | 
                                    Pembeli: <?php echo e($payment->order->user->name); ?> (<?php echo e($payment->order->user->email); ?>)
                                </p>
                            </div>
                            <div class="mb-4 bg-black/20 p-2 rounded-lg">
                                <?php
                                    $proofUrl = $payment->getProofUrl();
                                ?>
                                
                                <?php if($payment->isProofImage()): ?>
                                <img src="<?php echo e($proofUrl); ?>" 
                                     alt="Payment Proof" 
                                     class="w-full rounded-lg border-2 border-white/20 cursor-pointer hover:opacity-90 transition-opacity"
                                     onclick="window.open('<?php echo e($proofUrl); ?>', '_blank')"
                                     title="Klik untuk membuka di tab baru">
                                <?php elseif($payment->isProofPdf()): ?>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                                        <span class="text-3xl">📄</span>
                                        <div class="flex-1">
                                            <p class="font-semibold">File PDF</p>
                                            <p class="text-xs text-white/60"><?php echo e(basename($payment->proof_path)); ?></p>
                                        </div>
                                    </div>
                                    <iframe src="<?php echo e($proofUrl); ?>" 
                                            class="w-full h-96 rounded-lg border-2 border-white/20"
                                            frameborder="0">
                                    </iframe>
                                </div>
                                <?php else: ?>
                                <div class="p-4 text-center">
                                    <span class="text-4xl block mb-2">📎</span>
                                    <p class="font-semibold mb-1">File Bukti Pembayaran</p>
                                    <p class="text-sm text-white/60"><?php echo e(basename($payment->proof_path)); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex gap-3">
                                <a href="<?php echo e($proofUrl); ?>" 
                                   target="_blank" 
                                   class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center transition-colors cursor-pointer flex items-center justify-center gap-2">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-4 h-4']); ?>
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
                                    Buka di Tab Baru (Ukuran Penuh)
                                </a>
                                <button type="button"
                                        onclick="
                                            const modal = document.getElementById('proof-modal-<?php echo e($payment->id); ?>');
                                            if (modal) {
                                                modal.style.display = 'none';
                                                document.body.style.overflow = '';
                                            }
                                        "
                                        class="px-4 py-2 glass glass-hover rounded-lg transition-colors cursor-pointer">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Reject Modal -->
                    <?php if($payment->status === 'pending'): ?>
                    <div id="reject-modal-<?php echo e($payment->id); ?>"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm"
                         style="display: none;"
                         onclick="
                             if (event.target === this) {
                                 this.style.display = 'none';
                                 document.body.style.overflow = '';
                             }
                         ">
                        <div class="glass p-4 sm:p-6 rounded-lg max-w-md w-full mx-4 shadow-2xl"
                             onclick="event.stopPropagation()">
                            <h3 class="text-xl font-semibold mb-4">Tolak Pembayaran</h3>
                            <form method="POST" action="<?php echo e(route('admin.payments.reject', $payment)); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Alasan Penolakan *</label>
                                    <textarea name="rejection_reason" rows="4" required
                                              class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                                </div>
                                <div class="flex space-x-3">
                                    <button type="submit" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors">
                                        Tolak
                                    </button>
                                    <button type="button"
                                            onclick="
                                                const modal = document.getElementById('reject-modal-<?php echo e($payment->id); ?>');
                                                if (modal) {
                                                    modal.style.display = 'none';
                                                    document.body.style.overflow = '';
                                                }
                                            "
                                            class="flex-1 px-4 py-2 glass glass-hover rounded-lg transition-colors cursor-pointer">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            <?php echo e($payments->links()); ?>

        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-white/60 text-sm sm:text-base">Tidak ada payment dengan status ini.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>