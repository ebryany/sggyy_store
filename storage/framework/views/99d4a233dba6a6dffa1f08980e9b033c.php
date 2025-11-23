<?php $__env->startSection('title', 'Transaksi - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">Riwayat Transaksi</h1>
    
    <!-- Filter -->
    <div class="glass p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
        <form method="GET" action="<?php echo e(route('orders.index')); ?>" class="space-y-4">
            <!-- Row 1: Search & Status -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <input type="text" 
                       name="search" 
                       value="<?php echo e(request('search')); ?>" 
                       placeholder="Cari order number atau produk/jasa..." 
                       class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                
                <select name="status" 
                        class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?> class="bg-dark text-white">Pending</option>
                    <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?> class="bg-dark text-white">Paid</option>
                    <option value="processing" <?php echo e(request('status') == 'processing' ? 'selected' : ''); ?> class="bg-dark text-white">Processing</option>
                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?> class="bg-dark text-white">Completed</option>
                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?> class="bg-dark text-white">Cancelled</option>
                </select>

                <select name="type" 
                        class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Tipe</option>
                    <option value="product" <?php echo e(request('type') == 'product' ? 'selected' : ''); ?> class="bg-dark text-white">Produk</option>
                    <option value="service" <?php echo e(request('type') == 'service' ? 'selected' : ''); ?> class="bg-dark text-white">Jasa</option>
                </select>
            </div>

            <!-- Row 2: Date Range & Sort -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs text-white/60 mb-1">Tanggal Mulai</label>
                    <input type="date" 
                           name="date_from" 
                           value="<?php echo e(request('date_from')); ?>" 
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>
                
                <div>
                    <label class="block text-xs text-white/60 mb-1">Tanggal Akhir</label>
                    <input type="date" 
                           name="date_to" 
                           value="<?php echo e(request('date_to')); ?>" 
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Urutkan</label>
                    <select name="sort" class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?> class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?> class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                        <option value="status" <?php echo e(request('sort') == 'status' ? 'selected' : ''); ?> class="bg-dark text-white">Status</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Actions & Per Page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="px-4 sm:px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-base sm:text-sm touch-target">
                        Terapkan Filter
                    </button>
                    
                    <?php if(request()->anyFilled(['search', 'status', 'type', 'date_from', 'date_to', 'sort', 'per_page'])): ?>
                    <a href="<?php echo e(route('orders.index')); ?>" class="px-4 sm:px-6 py-2 glass glass-hover rounded-lg text-center text-base sm:text-sm touch-target">
                        Reset
                    </a>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-white/60">Per Halaman:</label>
                    <select name="per_page" onchange="this.form.submit()" class="glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="10" <?php echo e(request('per_page', 15) == 10 ? 'selected' : ''); ?> class="bg-dark text-white">10</option>
                        <option value="15" <?php echo e(request('per_page', 15) == 15 ? 'selected' : ''); ?> class="bg-dark text-white">15</option>
                        <option value="20" <?php echo e(request('per_page') == 20 ? 'selected' : ''); ?> class="bg-dark text-white">20</option>
                        <option value="30" <?php echo e(request('per_page') == 30 ? 'selected' : ''); ?> class="bg-dark text-white">30</option>
                        <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?> class="bg-dark text-white">50</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Orders Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <?php if($orders->count() > 0): ?>
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Order Number</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Produk/Jasa</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Pembeli</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Total</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Payment</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4 font-mono text-xs sm:text-sm"><?php echo e($order->order_number); ?></td>
                        <td class="py-3 px-4 text-sm">
                            <?php if($order->type === 'product' && $order->product): ?>
                                <span class="truncate block max-w-xs"><?php echo e($order->product->title); ?></span>
                            <?php elseif($order->type === 'service' && $order->service): ?>
                                <span class="truncate block max-w-xs"><?php echo e($order->service->title); ?></span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-sm">
                            <?php if($order->user): ?>
                                <span class="text-white/90"><?php echo e($order->user->name); ?></span>
                            <?php else: ?>
                                <span class="text-white/40">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 font-semibold text-sm">
                            <?php
                                $total = $order->total;
                                $total = is_numeric($total) ? (float)$total : 0;
                            ?>
                            <?php if($total > 0): ?>
                                <span class="text-primary">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                            <?php else: ?>
                                <span class="text-white/40">Rp 0</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($order->payment): ?>
                                <span class="text-xs text-white/70 font-medium"><?php echo e($order->payment->getMethodDisplayName()); ?></span>
                            <?php else: ?>
                                <span class="text-xs text-white/40 italic">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($order->status): ?>
                                <?php echo $__env->make('components.order-status-badge', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <span class="text-xs text-white/40 italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-white/60 text-xs sm:text-sm"><?php echo e($order->created_at->format('d M Y')); ?></td>
                        <td class="py-3 px-4">
                            <a href="<?php echo e(route('orders.show', $order)); ?>" 
                               class="text-primary hover:underline text-sm touch-target">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('orders.show', $order)); ?>" class="block glass glass-hover p-4 rounded-lg touch-target">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="font-mono text-xs text-white/60 mb-1"><?php echo e($order->order_number); ?></p>
                        <p class="font-semibold text-sm truncate">
                            <?php if($order->type === 'product' && $order->product): ?>
                                <?php echo e($order->product->title); ?>

                            <?php elseif($order->type === 'service' && $order->service): ?>
                                <?php echo e($order->service->title); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="ml-2 flex-shrink-0 flex flex-col gap-2 items-end">
                        <?php echo $__env->make('components.order-status-badge', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php if($order->canBeRated()): ?>
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded text-xs text-yellow-400">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-3 h-3']); ?>
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
                            <span>Rating</span>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <div>
                        <span class="font-bold text-primary text-base">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></span>
                        <?php if($order->payment): ?>
                            <p class="text-xs text-white/60 mt-1"><?php echo e($order->payment->getMethodDisplayName()); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-white/60"><?php echo e($order->created_at->format('d M Y')); ?></span>
                        <?php if($order->canBeRated()): ?>
                        <a href="<?php echo e(route('ratings.create', $order)); ?>" 
                           class="text-yellow-400 hover:text-yellow-300 text-xs touch-target flex items-center gap-1">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-3 h-3']); ?>
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
                            <span>Beri Rating</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($orders->links()); ?>

        </div>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-white/60 text-lg mb-4">Belum ada transaksi.</p>
            <a href="<?php echo e(route('products.index')); ?>" 
               class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                Mulai Belanja
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/orders/index.blade.php ENDPATH**/ ?>