

<?php $__env->startSection('title', 'Analytics - Seller Dashboard - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Analytics</h1>
            <p class="text-white/60 text-sm sm:text-base">Analisis pendapatan dan tren pesanan Anda</p>
        </div>
        <a href="<?php echo e(route('seller.dashboard')); ?>" 
           class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20 flex items-center gap-2">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-left','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'w-4 h-4']); ?>
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
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Revenue Chart -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'trending-up','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trending-up','class' => 'w-6 h-6 text-primary']); ?>
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
                        <h2 class="text-xl sm:text-2xl font-bold">Revenue</h2>
                        <p class="text-sm text-white/60">6 Bulan Terakhir</p>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-80 flex items-end justify-between gap-2 sm:gap-3 min-w-[400px] pb-4">
                <?php
                    $revenueValues = !empty($revenueChart) ? array_column($revenueChart, 'revenue') : [];
                    $maxRevenue = !empty($revenueValues) && max($revenueValues) > 0 ? max($revenueValues) : 1;
                ?>
                <?php if(!empty($revenueChart)): ?>
                    <?php $__currentLoopData = $revenueChart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-1 flex flex-col items-center group">
                        <?php
                            $heightPercent = $maxRevenue > 0 && $data['revenue'] > 0 ? max(40, ($data['revenue'] / $maxRevenue) * 100) : ($data['revenue'] > 0 ? 40 : 0);
                        ?>
                        <div class="w-full bg-gradient-to-t from-primary via-primary/80 to-primary/60 rounded-t-xl mb-2 hover:from-primary hover:via-primary/90 hover:to-primary/80 transition-all duration-300 cursor-pointer relative" 
                             style="height: <?php echo e($heightPercent); ?>%"
                             title="Rp <?php echo e(number_format($data['revenue'], 0, ',', '.')); ?>">
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-dark/90 px-2 py-1 rounded-lg text-xs font-semibold text-primary whitespace-nowrap pointer-events-none z-10">
                                Rp <?php echo e(number_format($data['revenue'], 0, ',', '.')); ?>

                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-white/60 truncate w-full text-center font-medium"><?php echo e($data['month']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="flex-1 flex items-center justify-center h-full">
                        <p class="text-white/40 text-sm">Belum ada data revenue</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Order Trend -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chart','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chart','class' => 'w-6 h-6 text-primary']); ?>
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
                        <h2 class="text-xl sm:text-2xl font-bold">Order Trend</h2>
                        <p class="text-sm text-white/60">30 Hari Terakhir</p>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-80 flex items-end justify-between gap-1 sm:gap-2 min-w-[500px] pb-4">
                <?php
                    $last14Days = !empty($orderTrend) ? array_slice($orderTrend, -14) : [];
                    $orderValues = !empty($orderTrend) ? array_column($orderTrend, 'orders') : [];
                    $maxOrders = !empty($orderValues) && max($orderValues) > 0 ? max($orderValues) : 1;
                ?>
                <?php if(!empty($last14Days)): ?>
                    <?php $__currentLoopData = $last14Days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex-1 flex flex-col items-center group">
                        <?php
                            $heightPercent = $maxOrders > 0 && $data['orders'] > 0 ? max(30, ($data['orders'] / $maxOrders) * 100) : ($data['orders'] > 0 ? 30 : 0);
                        ?>
                        <div class="w-full bg-gradient-to-t from-blue-500 via-blue-400 to-blue-300 rounded-t-lg mb-2 hover:from-blue-400 hover:via-blue-300 hover:to-blue-200 transition-all duration-300 cursor-pointer relative" 
                             style="height: <?php echo e($heightPercent); ?>%"
                             title="<?php echo e($data['orders']); ?> pesanan">
                            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-dark/90 px-2 py-1 rounded-lg text-xs font-semibold text-blue-400 whitespace-nowrap pointer-events-none z-10">
                                <?php echo e($data['orders']); ?> pesanan
                            </div>
                        </div>
                        <p class="text-[10px] sm:text-xs text-white/60 transform -rotate-45 origin-bottom-left whitespace-nowrap" style="writing-mode: vertical-rl;"><?php echo e($data['date']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="flex-1 flex items-center justify-center h-full">
                        <p class="text-white/40 text-sm">Belum ada data pesanan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('seller.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/seller/analytics/index.blade.php ENDPATH**/ ?>