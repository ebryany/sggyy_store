<?php $__env->startSection('title', 'Detail Pesanan - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-3 sm:space-y-6 pb-24 lg:pb-0">
    <!-- Back Button - Compact Mobile -->
    <div class="mb-3 sm:mb-6">
        <a href="<?php echo e(route('orders.index')); ?>" class="text-primary hover:underline flex items-center space-x-2 touch-target text-xs sm:text-base">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-left','class' => 'w-4 h-4 sm:w-5 sm:h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'w-4 h-4 sm:w-5 sm:h-5']); ?>
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
            <span>Kembali</span>
        </a>
    </div>
    
    <!-- Order Header Card -->
    <div class="mb-3 sm:mb-4">
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5">
            <!-- Mobile: Compact Header -->
            <div class="sm:hidden">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-sm font-semibold text-white/90 mb-1">No. Pesanan</h2>
                        <p class="text-xs font-medium text-white truncate"><?php echo e($order->order_number); ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="px-2.5 py-1 bg-primary/20 text-primary rounded-lg text-[10px] font-semibold">
                            <?php if($order->status === 'completed'): ?>
                                SELESAI
                            <?php elseif($order->status === 'processing'): ?>
                                DIPROSES
                            <?php elseif($order->status === 'paid'): ?>
                                DIBAYAR
                            <?php elseif($order->status === 'waiting_confirmation'): ?>
                                MENUNGGU
                            <?php else: ?>
                                <?php echo e(strtoupper($order->status)); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Desktop: Original Header -->
            <div class="hidden sm:block">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg sm:text-xl font-semibold">No. Pesanan. <?php echo e($order->order_number); ?></h2>
                    <div class="flex items-center gap-2">
                        <span class="text-xs sm:text-sm text-white/60">|</span>
                        <span class="text-sm sm:text-base font-semibold text-primary">
                            <?php if($order->status === 'completed'): ?>
                                PESANAN SELESAI
                            <?php elseif($order->status === 'processing'): ?>
                                SEDANG DIPROSES
                            <?php elseif($order->status === 'paid'): ?>
                                PESANAN DIBAYAR
                            <?php elseif($order->status === 'waiting_confirmation'): ?>
                                MENUNGGU KONFIRMASI
                            <?php else: ?>
                                <?php echo e(strtoupper($order->status)); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Timeline Card - Separate Card at Top -->
    <div class="mb-4 sm:mb-8">
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5">
            <h3 class="text-sm sm:text-base font-semibold mb-4 text-white/90">Timeline Pesanan</h3>
            <?php echo $__env->make('components.order-timeline', ['timeline' => $timeline], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-3 sm:space-y-6">
            <!-- Order Info - Optimized for Mobile -->
            <div class="glass p-4 sm:p-6 rounded-xl border border-white/5">
                <!-- Mobile: Compact Header -->
                <div class="sm:hidden mb-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-base font-bold mb-1 break-words text-white">Order #<?php echo e($order->order_number); ?></h1>
                            <p class="text-xs text-white/50">Dibuat <?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <?php echo $__env->make('components.order-status-badge', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Desktop: Original Header -->
                <div class="hidden sm:flex flex-col sm:flex-row justify-between items-start sm:items-start gap-3 sm:gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold mb-2 break-words">Order #<?php echo e($order->order_number); ?></h1>
                        <p class="text-white/60 text-sm sm:text-base">Dibuat pada <?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <?php echo $__env->make('components.order-status-badge', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
                
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold text-sm sm:text-base mb-3">Detail Item</h3>
                    <!-- Mobile: Vertical Layout -->
                    <div class="sm:hidden space-y-3">
                        <div class="flex items-start gap-3">
                            <?php if($order->type === 'product' && $order->product): ?>
                                <?php if($order->product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $order->product->image)); ?>" 
                                     alt="<?php echo e($order->product->title); ?>" 
                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm mb-1 break-words text-white"><?php echo e($order->product->title); ?></h4>
                                    <p class="text-white/50 text-xs"><?php echo e($order->product->category); ?></p>
                                </div>
                            <?php elseif($order->type === 'service' && $order->service): ?>
                                <?php if($order->service->image): ?>
                                <img src="<?php echo e(asset('storage/' . $order->service->image)); ?>" 
                                     alt="<?php echo e($order->service->title); ?>" 
                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-sm mb-1 break-words text-white"><?php echo e($order->service->title); ?></h4>
                                    <p class="text-white/50 text-xs">Durasi: <?php echo e($order->service->duration_hours); ?> jam</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="pt-3 border-t border-white/10">
                            <p class="text-lg font-bold text-primary">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    
                    <!-- Desktop: Horizontal Layout -->
                    <div class="hidden sm:flex items-center space-x-4">
                        <?php if($order->type === 'product' && $order->product): ?>
                            <?php if($order->product->image): ?>
                            <img src="<?php echo e(asset('storage/' . $order->product->image)); ?>" 
                                 alt="<?php echo e($order->product->title); ?>" 
                                 class="w-20 h-20 object-cover rounded-lg">
                            <?php endif; ?>
                            <div class="flex-1">
                                <h4 class="font-semibold"><?php echo e($order->product->title); ?></h4>
                                <p class="text-white/60 text-sm"><?php echo e($order->product->category); ?></p>
                            </div>
                        <?php elseif($order->type === 'service' && $order->service): ?>
                            <?php if($order->service->image): ?>
                            <img src="<?php echo e(asset('storage/' . $order->service->image)); ?>" 
                                 alt="<?php echo e($order->service->title); ?>" 
                                 class="w-20 h-20 object-cover rounded-lg">
                            <?php endif; ?>
                            <div class="flex-1">
                                <h4 class="font-semibold"><?php echo e($order->service->title); ?></h4>
                                <p class="text-white/60 text-sm">Durasi: <?php echo e($order->service->duration_hours); ?> jam</p>
                            </div>
                        <?php endif; ?>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-primary">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if($order->notes): ?>
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold text-sm sm:text-base mb-2">Catatan</h3>
                    <p class="text-white/70 text-sm sm:text-base"><?php echo e($order->notes); ?></p>
                </div>
                <?php endif; ?>
                
                <!-- Task File (for Service Orders) -->
                <?php if($order->type === 'service' && $order->task_file_path): ?>
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-5 h-5 text-primary']); ?>
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
                        File Tugas
                    </h3>
                    <?php
                        $taskFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->task_file_path);
                        $taskFileName = basename($order->task_file_path);
                        $taskFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->task_file_path);
                    ?>
                    
                    <?php if($taskFileExists): ?>
                    <div class="glass p-4 rounded-lg">
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-6 h-6 text-primary']); ?>
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
                                <p class="font-semibold">File Tugas dari Buyer</p>
                                <p class="text-xs text-white/60"><?php echo e($taskFileName); ?></p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <?php if($isSeller || $isAdmin): ?>
                            <a href="<?php echo e(route('orders.downloadTask', $order)); ?>" 
                               class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-5 h-5 inline mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-5 h-5 inline mr-2']); ?>
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
                                Download File Tugas
                            </a>
                            <?php endif; ?>
                            <?php if($isOwner): ?>
                            <p class="text-xs text-white/60 text-center">
                                File ini dapat diakses oleh seller untuk mengerjakan tugas Anda.
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                        <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-5 h-5']); ?>
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
                            File Tugas Tidak Ditemukan
                        </p>
                        <p class="text-sm text-yellow-300/80">File tugas tidak dapat ditemukan di storage. Silakan hubungi admin.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Deliverable File (for Service Orders) -->
                <?php if($order->type === 'service' && $order->deliverable_path && !($order->status === 'waiting_confirmation' && $isOwner)): ?>
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-5 h-5 text-primary']); ?>
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
                        Hasil Pekerjaan
                    </h3>
                    <?php
                        $deliverableFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->deliverable_path);
                        $deliverableFileName = basename($order->deliverable_path);
                        $deliverableFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                        $fileExtension = strtolower(pathinfo($deliverableFileName, PATHINFO_EXTENSION));
                        $canPreview = in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                    ?>
                    
                    <?php if($deliverableFileExists): ?>
                    <div class="glass p-4 rounded-lg">
                        <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-6 h-6 text-green-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-6 h-6 text-green-400']); ?>
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
                                <p class="font-semibold">File Hasil Pekerjaan</p>
                                <p class="text-xs text-white/60"><?php echo e($deliverableFileName); ?></p>
                            </div>
                        </div>
                        
                        <!-- Preview Section (for PDF and Images) -->
                        <?php if($canPreview && ($isOwner || $isAdmin)): ?>
                        <div class="mb-4 p-3 bg-white/5 rounded-lg border border-white/10">
                            <p class="text-xs text-white/60 mb-2 flex items-center gap-1">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'eye','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'w-3 h-3']); ?>
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
                                Preview:
                            </p>
                            <div class="rounded-lg overflow-hidden bg-white/5 max-h-96 overflow-y-auto">
                                <?php if($fileExtension === 'pdf'): ?>
                                    <iframe src="<?php echo e($deliverableFileUrl); ?>#toolbar=0" 
                                            class="w-full h-96 border-0"
                                            style="min-height: 400px;">
                                    </iframe>
                                <?php else: ?>
                                    <img src="<?php echo e($deliverableFileUrl); ?>" 
                                         alt="Preview <?php echo e($deliverableFileName); ?>"
                                         class="w-full h-auto object-contain">
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="space-y-2">
                            <?php if($isSeller || $isAdmin): ?>
                            <div class="flex gap-2">
                                <a href="<?php echo e(route('orders.downloadDeliverable', $order)); ?>" 
                                   target="_blank"
                                   class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-5 h-5 inline mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-5 h-5 inline mr-2']); ?>
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
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4 inline mr-1']); ?>
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
                                    Hapus
                                </button>
                                
                                <!-- Delete Deliverable Confirmation Modal -->
                                <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-deliverable-modal','title' => 'Hapus Hasil Pekerjaan','message' => 'Apakah Anda yakin ingin menghapus hasil pekerjaan ini? Buyer akan mendapat notifikasi.','confirmText' => 'Ya, Hapus','cancelText' => 'Batal','type' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-deliverable-modal','title' => 'Hapus Hasil Pekerjaan','message' => 'Apakah Anda yakin ingin menghapus hasil pekerjaan ini? Buyer akan mendapat notifikasi.','confirm-text' => 'Ya, Hapus','cancel-text' => 'Batal','type' => 'danger']); ?>
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
                                
                                <form id="delete-deliverable-form" method="POST" action="<?php echo e(route('orders.deleteDeliverable', $order)); ?>" style="display: none;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
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
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'lightbulb','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lightbulb','class' => 'w-3 h-3']); ?>
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
                                    Upload ulang untuk mengganti file hasil pekerjaan
                                </span>
                            </p>
                            <?php elseif($isOwner && $order->payment && $order->payment->status === 'verified'): ?>
                            <a href="<?php echo e(route('orders.downloadDeliverable', $order)); ?>" 
                               target="_blank"
                               class="block px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-center font-semibold transition-colors">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-5 h-5 inline mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-5 h-5 inline mr-2']); ?>
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
                                Download Hasil Pekerjaan
                            </a>
                            <?php elseif($isOwner): ?>
                            <p class="text-xs text-yellow-400 text-center">
                                <span class="flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-3 h-3']); ?>
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
                                    Hasil pekerjaan akan tersedia setelah pembayaran diverifikasi
                                </span>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                        <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-5 h-5']); ?>
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
                            File Hasil Pekerjaan Tidak Ditemukan
                        </p>
                        <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan hubungi admin.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Payment Info -->
            <?php if($order->payment): ?>
            <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 mb-3 sm:mb-6">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-white/60">Metode Pembayaran</span>
                        <span class="font-semibold"><?php echo e($order->payment->getMethodDisplayName()); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Status Pembayaran</span>
                        <?php if($order->payment->status === 'verified'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">Verified</span>
                        <?php elseif($order->payment->status === 'pending'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Pending</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">Rejected</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Xendit Invoice Link -->
                    <?php if($order->payment->isXenditPayment() && $order->payment->xendit_metadata): ?>
                        <?php
                            $invoiceUrl = $order->payment->xendit_metadata['invoice_url'] ?? null;
                            $xenditStatus = $order->payment->xendit_metadata['status'] ?? 'PENDING';
                            $useXenPlatform = isset($featureFlags) && ($featureFlags['enable_xenplatform'] ?? false);
                        ?>
                        <?php if($invoiceUrl && $order->payment->status === 'pending'): ?>
                        <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                            <div class="flex items-center gap-2 mb-3">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'external-link','class' => 'w-5 h-5 text-blue-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'external-link','class' => 'w-5 h-5 text-blue-400']); ?>
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
                                <h3 class="font-semibold text-blue-400 flex items-center gap-2">
                                    Pembayaran via Xendit
                                    <?php if($useXenPlatform): ?>
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shield-check','class' => 'w-3 h-3 inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-3 h-3 inline']); ?>
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
                                            xenPlatform
                                        </span>
                                    <?php endif; ?>
                                </h3>
                            </div>
                            <p class="text-sm text-white/70 mb-3">
                                Klik tombol di bawah untuk melakukan pembayaran. Verifikasi akan dilakukan otomatis setelah pembayaran berhasil.
                                <?php if($useXenPlatform): ?>
                                    <br><span class="text-purple-400 font-semibold">Dana akan langsung di-split ke seller sub-account saat verified.</span>
                                <?php endif; ?>
                            </p>
                            <a href="<?php echo e($invoiceUrl); ?>" 
                               target="_blank"
                               class="block w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 rounded-lg text-center font-semibold transition-all hover:scale-105 shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'external-link','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'external-link','class' => 'w-5 h-5']); ?>
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
                                <span>Bayar Sekarang</span>
                            </a>
                            <?php if($xenditStatus === 'PENDING'): ?>
                            <p class="text-xs text-white/60 mt-2 text-center">
                                Status: <span class="text-yellow-400">Menunggu Pembayaran</span>
                            </p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Bank Account Info untuk Bank Transfer -->
                    <?php if($order->payment->method === 'bank_transfer' && $bankAccountInfo && $bankAccountInfo['bank_account_number']): ?>
                    <div class="mt-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <h3 class="font-semibold text-blue-400 mb-3 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-5 h-5 text-blue-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-5 h-5 text-blue-400']); ?>
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
                            <span>Informasi Rekening Bank</span>
                        </h3>
                        <div class="space-y-2 text-sm">
                            <?php if($bankAccountInfo['bank_name']): ?>
                            <div class="flex justify-between">
                                <span class="text-white/60">Bank</span>
                                <span class="font-semibold"><?php echo e($bankAccountInfo['bank_name']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if($bankAccountInfo['bank_account_number']): ?>
                            <div class="flex justify-between">
                                <span class="text-white/60">Nomor Rekening</span>
                                <span class="font-semibold font-mono text-primary"><?php echo e($bankAccountInfo['bank_account_number']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if($bankAccountInfo['bank_account_name']): ?>
                            <div class="flex justify-between">
                                <span class="text-white/60">Nama Pemilik</span>
                                <span class="font-semibold"><?php echo e($bankAccountInfo['bank_account_name']); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-white/60 mt-3 pt-3 border-t border-white/10">
                            <span class="flex items-center gap-1">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-4 h-4']); ?>
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
                                Transfer sesuai dengan nominal pesanan: <strong class="text-primary">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></strong>
                            </span>
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- QRIS Info -->
                    <?php if($order->payment->method === 'qris' && $bankAccountInfo && $bankAccountInfo['qris_code']): ?>
                    <div class="mt-4 p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                        <h3 class="font-semibold text-green-400 mb-3 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mobile','class' => 'w-5 h-5 text-green-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','class' => 'w-5 h-5 text-green-400']); ?>
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
                            <span>QRIS Payment</span>
                        </h3>
                        <div class="text-center">
                            <img src="<?php echo e($bankAccountInfo['qris_code']); ?>" alt="QRIS Code" class="mx-auto max-w-xs rounded-lg mb-3">
                            <p class="text-xs text-white/60">
                                Scan QR code di atas untuk melakukan pembayaran
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Warning untuk upload bukti -->
                    <?php if(in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending' && !$order->payment->proof_path): ?>
                    <div class="mt-4 p-4 bg-yellow-500/20 border-2 border-yellow-500/30 rounded-lg">
                        <div class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-6 h-6 text-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-6 h-6 text-yellow-400']); ?>
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
                                <p class="font-semibold text-yellow-400 mb-1">Upload Bukti Pembayaran Diperlukan!</p>
                                <p class="text-sm text-white/80">
                                    Anda menggunakan metode <?php echo e($order->payment->getMethodDisplayName()); ?>. 
                                    Silakan upload bukti pembayaran Anda untuk proses verifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($order->payment->verified_at): ?>
                    <div class="flex justify-between">
                        <span class="text-white/60">Diverifikasi pada</span>
                        <span><?php echo e($order->payment->verified_at->format('d M Y, H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($order->payment->proof_path): ?>
                    <div class="mt-4">
                        <p class="text-white/60 mb-2">Bukti Pembayaran</p>
                        <?php
                            $proofUrl = $order->payment->getProofUrl();
                            $fileExists = $proofUrl !== null && \Illuminate\Support\Facades\Storage::disk('public')->exists($order->payment->proof_path);
                        ?>
                        
                        <?php if($fileExists && $order->payment->isProofImage()): ?>
                        <div class="glass p-4 rounded-lg">
                            <img src="<?php echo e($proofUrl); ?>" 
                                 alt="Payment Proof" 
                                 class="max-w-md w-full rounded-lg border-2 border-white/10 cursor-pointer hover:opacity-90 transition-opacity"
                                 onclick="window.open('<?php echo e($proofUrl); ?>', '_blank')"
                                 title="Klik untuk membuka di tab baru"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div style="display:none;" class="p-3 bg-yellow-500/20 text-yellow-400 rounded-lg border border-yellow-500/30">
                                <p class="text-sm flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-4 h-4']); ?>
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
                                    Gambar tidak dapat dimuat. <a href="<?php echo e($proofUrl); ?>" target="_blank" class="underline">Klik di sini untuk membuka</a>
                                </p>
                            </div>
                            <a href="<?php echo e($proofUrl); ?>" 
                               target="_blank" 
                               class="text-primary hover:underline text-sm mt-2 inline-block">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-4 h-4 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-4 h-4 inline mr-1']); ?>
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
                        </div>
                        <?php elseif($fileExists && $order->payment->isProofPdf()): ?>
                        <div class="glass p-4 rounded-lg">
                            <div class="flex items-center gap-3 mb-3 p-3 bg-white/5 rounded-lg">
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
                                    <p class="text-xs text-white/60"><?php echo e(basename($order->payment->proof_path)); ?></p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <a href="<?php echo e($proofUrl); ?>" 
                                   target="_blank" 
                                   class="block px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg text-center font-semibold transition-colors">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-4 h-4 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-4 h-4 inline mr-1']); ?>
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
                                    Download / Buka PDF
                                </a>
                                <iframe src="<?php echo e($proofUrl); ?>" 
                                        class="w-full h-96 rounded-lg border-2 border-white/10"
                                        frameborder="0"
                                        onerror="this.style.display='none';">
                                </iframe>
                                <p class="text-xs text-white/60 text-center">
                                    Jika PDF tidak muncul, <a href="<?php echo e($proofUrl); ?>" target="_blank" class="text-primary hover:underline">klik di sini untuk membuka di tab baru</a>
                                </p>
                            </div>
                        </div>
                        <?php elseif($fileExists): ?>
                        <div class="glass p-4 rounded-lg">
                            <a href="<?php echo e($proofUrl); ?>" 
                               target="_blank" 
                               class="flex items-center gap-3 p-3 bg-primary/20 hover:bg-primary/30 rounded-lg transition-colors">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-6 h-6 text-primary']); ?>
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
                                    <p class="font-semibold">Bukti Pembayaran</p>
                                    <p class="text-xs text-white/60"><?php echo e(basename($order->payment->proof_path)); ?></p>
                                </div>
                                <span class="text-primary">→</span>
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="glass p-4 rounded-lg bg-yellow-500/20 border border-yellow-500/30">
                            <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-5 h-5']); ?>
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
                                File Bukti Pembayaran Tidak Ditemukan
                            </p>
                            <p class="text-sm text-yellow-300/80">File bukti pembayaran tidak dapat ditemukan di storage. Silakan hubungi admin atau upload ulang bukti pembayaran.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Action Buttons (Mobile: Below Payment Info) -->
            <?php
                $seller = null;
                if ($order->type === 'product' && $order->product) {
                    $seller = $order->product->user;
                } elseif ($order->type === 'service' && $order->service) {
                    $seller = $order->service->user;
                }
            ?>
            
            <?php if($order->rating): ?>
            <div class="lg:hidden space-y-3">
                <?php if($seller): ?>
                <a href="<?php echo e(route('chat.show', '@' . $seller->username)); ?>" 
                   class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                    Hubungi Penjual
                </a>
                <?php endif; ?>
                
                <?php if($order->type === 'product' && $order->product): ?>
                <a href="<?php echo e(route('products.show', $order->product)); ?>" 
                   class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                    Beli Lagi
                </a>
                <?php elseif($order->type === 'service' && $order->service): ?>
                <a href="<?php echo e(route('services.show', $order->service)); ?>" 
                   class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                    Beli Lagi
                </a>
                <?php endif; ?>
                
                <?php if($order->payment): ?>
                <a href="<?php echo e(route('orders.show', $order)); ?>#payment" 
                   class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                    Lihat Tagihan
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Waiting Confirmation Section (Buyer Only) -->
            <?php if($order->type === 'service' && $order->status === 'waiting_confirmation' && $isOwner): ?>
            <div class="glass p-6 rounded-xl border-2 border-primary/50 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 mb-6">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-primary/30 flex items-center justify-center">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-6 h-6 text-primary']); ?>
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
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-1 text-white">Review Hasil Pekerjaan</h2>
                        <p class="text-sm text-white/70">Seller telah mengupload hasil pekerjaan. Silakan review dan konfirmasi.</p>
                    </div>
                </div>
                
                <!-- Deliverable Preview (if exists) -->
                <?php if($order->deliverable_path): ?>
                <?php
                    $deliverableFileUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($order->deliverable_path);
                    $deliverableFileName = basename($order->deliverable_path);
                    $deliverableFileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($order->deliverable_path);
                    $fileExtension = strtolower(pathinfo($deliverableFileName, PATHINFO_EXTENSION));
                    $canPreview = in_array($fileExtension, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp']);
                ?>
                
                <?php if($deliverableFileExists): ?>
                <div class="mb-4 p-4 bg-white/5 rounded-lg border border-white/10">
                    <div class="flex items-center gap-3 mb-3">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-6 h-6 text-green-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-6 h-6 text-green-400']); ?>
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
                            <p class="font-semibold">File Hasil Pekerjaan</p>
                            <p class="text-xs text-white/60"><?php echo e($deliverableFileName); ?></p>
                        </div>
                    </div>
                    
                    <!-- Preview Section (for PDF and Images) -->
                    <?php if($canPreview): ?>
                    <div class="mb-3 p-3 bg-white/5 rounded-lg border border-white/10">
                        <p class="text-xs text-white/60 mb-2 flex items-center gap-1">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'eye','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'w-3 h-3']); ?>
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
                            Preview:
                        </p>
                        <div class="rounded-lg overflow-hidden bg-white/5 max-h-96 overflow-y-auto">
                            <?php if($fileExtension === 'pdf'): ?>
                                <iframe src="<?php echo e($deliverableFileUrl); ?>#toolbar=0" 
                                        class="w-full h-96 border-0"
                                        style="min-height: 400px;">
                                </iframe>
                            <?php else: ?>
                                <img src="<?php echo e($deliverableFileUrl); ?>" 
                                     alt="Preview <?php echo e($deliverableFileName); ?>"
                                     class="w-full h-auto object-contain">
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('orders.downloadDeliverable', $order)); ?>" 
                       target="_blank"
                       class="block px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-center font-semibold transition-colors">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'download','class' => 'w-5 h-5 inline mr-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'download','class' => 'w-5 h-5 inline mr-2']); ?>
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
                        Download Hasil Pekerjaan
                    </a>
                </div>
                <?php else: ?>
                <div class="mb-4 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                    <p class="text-yellow-400 font-semibold mb-2 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-5 h-5']); ?>
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
                        File Hasil Pekerjaan Tidak Ditemukan
                    </p>
                    <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan hubungi seller atau admin.</p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                
                <!-- Countdown Timer -->
                <?php if($order->auto_complete_at): ?>
                <div class="mb-4 p-3 bg-white/5 rounded-lg border border-white/10" 
                     x-data="{
                         timeLeft: <?php echo e($order->auto_complete_at->diffInSeconds(now())); ?>,
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
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'timer','class' => 'w-4 h-4 text-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'timer','class' => 'w-4 h-4 text-yellow-400']); ?>
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
                        <span class="text-xs text-white/60">Waktu tersisa untuk review:</span>
                        <span class="text-lg font-bold text-yellow-400" x-text="formatted"></span>
                    </div>
                    <p class="text-xs text-white/50 mt-1">Jika tidak ada respon dalam waktu ini, order akan otomatis diselesaikan.</p>
                </div>
                <?php endif; ?>
                
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
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'refresh','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'refresh','class' => 'w-5 h-5']); ?>
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
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-6 h-6 text-green-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-6 h-6 text-green-400']); ?>
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
                            <form id="confirm-completion-form" method="POST" action="<?php echo e(route('orders.confirm', $order)); ?>" class="flex-1">
                                <?php echo csrf_field(); ?>
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
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'refresh','class' => 'w-6 h-6 text-orange-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'refresh','class' => 'w-6 h-6 text-orange-400']); ?>
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
                            Minta Revisi
                        </h3>
                        
                        <form method="POST" action="<?php echo e(route('orders.requestRevision', $order)); ?>">
                            <?php echo csrf_field(); ?>
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
            <?php endif; ?>
            
             <!-- Order Management Controls (Seller/Admin Only) -->
            <?php if (isset($component)) { $__componentOriginalf9290ecd8e6a3362f336ef1e5497992f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf9290ecd8e6a3362f336ef1e5497992f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.order-progress-control','data' => ['order' => $order]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('order-progress-control'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['order' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf9290ecd8e6a3362f336ef1e5497992f)): ?>
<?php $attributes = $__attributesOriginalf9290ecd8e6a3362f336ef1e5497992f; ?>
<?php unset($__attributesOriginalf9290ecd8e6a3362f336ef1e5497992f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf9290ecd8e6a3362f336ef1e5497992f)): ?>
<?php $component = $__componentOriginalf9290ecd8e6a3362f336ef1e5497992f; ?>
<?php unset($__componentOriginalf9290ecd8e6a3362f336ef1e5497992f); ?>
<?php endif; ?>
            
            <!-- Alert untuk upload bukti pembayaran -->
            <?php if(session('upload_proof_required') || ($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending' && !$order->payment->proof_path)): ?>
            <div class="mb-4 sm:mb-6 glass p-4 sm:p-6 rounded-lg border-2 border-yellow-500/50 bg-yellow-500/10" 
                 x-data="{ show: true }"
                 x-show="show"
                 x-transition>
                <div class="flex items-start gap-4">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-8 h-8 text-yellow-400 flex-shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-8 h-8 text-yellow-400 flex-shrink-0']); ?>
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
                        <h3 class="font-bold text-yellow-400 mb-2 text-lg">Upload Bukti Pembayaran Diperlukan!</h3>
                        <p class="text-white/90 mb-3">
                            Anda menggunakan metode pembayaran <strong><?php echo e($order->payment->getMethodDisplayName()); ?></strong>. 
                            Silakan upload bukti pembayaran Anda untuk melanjutkan proses verifikasi.
                        </p>
                        <?php if($order->payment && in_array($order->payment->method, ['bank_transfer', 'qris']) && $order->payment->status === 'pending'): ?>
                        <label class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold transition-colors cursor-pointer inline-block"
                               x-data="{ uploading: false }"
                               @change="
                                   uploading = true;
                                   const form = new FormData();
                                   form.append('proof_path', $event.target.files[0]);
                                   form.append('_token', document.querySelector('meta[name=csrf-token]').content);
                                   
                                   fetch('<?php echo e(route('payments.upload', $order->payment)); ?>', {
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
                        <?php endif; ?>
                    </div>
                    <button @click="show = false" class="text-white/60 hover:text-white flex-shrink-0">✕</button>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Sidebar: Action Buttons -->
        <div class="lg:col-span-1">
            <!-- Mobile: Sticky Bottom (Hidden - buttons now appear below Payment Info) -->
            <div class="lg:hidden fixed bottom-0 left-0 right-0 glass border-t border-white/10 p-4 z-40 pb-safe hidden">
                <div class="max-w-md mx-auto space-y-2">
                <?php
                    $seller = null;
                    if ($order->type === 'product' && $order->product) {
                        $seller = $order->product->user;
                    } elseif ($order->type === 'service' && $order->service) {
                        $seller = $order->service->user;
                    }
                    
                    // 🔒 REKBER FLOW: Buyer dapat konfirmasi saat status processing, waiting_confirmation, atau completed dengan escrow holding
                    $canConfirmProduct = $isOwner && (
                        ($order->type === 'product' && in_array($order->status, ['processing', 'waiting_confirmation'])) ||
                        ($order->type === 'service' && $order->status === 'waiting_confirmation') ||
                        ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding())
                    );
                ?>
                
                <?php if($canConfirmProduct): ?>
                <form id="confirm-product-form-sidebar-<?php echo e($order->id); ?>" 
                      action="<?php echo e(route('orders.confirm', $order)); ?>" 
                      method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="button" 
                            onclick="
                                const modal = document.getElementById('confirm-product-modal-sidebar-<?php echo e($order->id); ?>');
                                if (modal) {
                                    modal.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                            "
                            class="block w-full px-4 py-3 bg-green-500 hover:bg-green-600 rounded-lg transition-all text-center font-semibold touch-target">
                        <span class="flex items-center justify-center gap-2">
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
                            <span>Konfirmasi Produk</span>
                        </span>
                    </button>
                </form>
                
                <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'confirm-product-modal-sidebar-'.e($order->id).'','title' => 'Konfirmasi Penerimaan Produk','message' => 'Apakah Anda yakin produk sudah diterima? Dana rekber akan otomatis diteruskan ke seller.','confirmText' => 'Ya, Konfirmasi','cancelText' => 'Batal','type' => 'warning','formId' => 'confirm-product-form-sidebar-'.e($order->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'confirm-product-modal-sidebar-'.e($order->id).'','title' => 'Konfirmasi Penerimaan Produk','message' => 'Apakah Anda yakin produk sudah diterima? Dana rekber akan otomatis diteruskan ke seller.','confirmText' => 'Ya, Konfirmasi','cancelText' => 'Batal','type' => 'warning','formId' => 'confirm-product-form-sidebar-'.e($order->id).'']); ?>
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
                <?php endif; ?>
                
                
                <?php if($order->rating): ?>
                    <?php if($seller): ?>
                    <a href="<?php echo e(route('chat.show', '@' . $seller->username)); ?>" 
                       class="block w-full px-4 py-2.5 glass hover:bg-white/5 rounded-lg transition-all text-center font-medium touch-target border border-white/10 text-sm">
                        Hubungi Penjual
                    </a>
                    <?php endif; ?>
                    
                    <?php if($order->type === 'product' && $order->product): ?>
                    <a href="<?php echo e(route('products.show', $order->product)); ?>" 
                       class="block w-full px-4 py-2.5 glass hover:bg-white/5 rounded-lg transition-all text-center font-medium touch-target border border-white/10 text-sm">
                        Beli Lagi
                    </a>
                    <?php elseif($order->type === 'service' && $order->service): ?>
                    <a href="<?php echo e(route('services.show', $order->service)); ?>" 
                       class="block w-full px-4 py-2.5 glass hover:bg-white/5 rounded-lg transition-all text-center font-medium touch-target border border-white/10 text-sm">
                        Beli Lagi
                    </a>
                    <?php endif; ?>
                    
                    <?php if($order->payment): ?>
                    <a href="<?php echo e(route('orders.show', $order)); ?>#payment" 
                       class="block w-full px-4 py-2.5 glass hover:bg-white/5 rounded-lg transition-all text-center font-medium touch-target border border-white/10 text-sm">
                        Lihat Tagihan
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
            
            <!-- Desktop: Sticky Sidebar -->
            <div class="hidden lg:block">
                <div class="glass p-6 rounded-xl border border-white/5 sticky top-20 space-y-3">
                <?php
                    $seller = null;
                    if ($order->type === 'product' && $order->product) {
                        $seller = $order->product->user;
                    } elseif ($order->type === 'service' && $order->service) {
                        $seller = $order->service->user;
                    }
                    
                    // 🔒 REKBER FLOW: Buyer dapat konfirmasi saat status processing, waiting_confirmation, atau completed dengan escrow holding
                    $canConfirmProduct = $isOwner && (
                        ($order->type === 'product' && in_array($order->status, ['processing', 'waiting_confirmation'])) ||
                        ($order->type === 'service' && $order->status === 'waiting_confirmation') ||
                        ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding())
                    );
                ?>
                
                <?php if($canConfirmProduct): ?>
                <form id="confirm-product-form-sidebar-desktop-<?php echo e($order->id); ?>" 
                      action="<?php echo e(route('orders.confirm', $order)); ?>" 
                      method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="button" 
                            onclick="
                                const modal = document.getElementById('confirm-product-modal-sidebar-desktop-<?php echo e($order->id); ?>');
                                if (modal) {
                                    modal.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                            "
                            class="block w-full px-4 py-3 bg-green-500 hover:bg-green-600 rounded-lg transition-all text-center font-semibold touch-target">
                        <span class="flex items-center justify-center gap-2">
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
                            <span>Konfirmasi Produk</span>
                        </span>
                    </button>
                </form>
                
                <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'confirm-product-modal-sidebar-desktop-'.e($order->id).'','title' => 'Konfirmasi Penerimaan Produk','message' => 'Apakah Anda yakin produk sudah diterima? Dana rekber akan otomatis diteruskan ke seller.','confirmText' => 'Ya, Konfirmasi','cancelText' => 'Batal','type' => 'warning','formId' => 'confirm-product-form-sidebar-desktop-'.e($order->id).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'confirm-product-modal-sidebar-desktop-'.e($order->id).'','title' => 'Konfirmasi Penerimaan Produk','message' => 'Apakah Anda yakin produk sudah diterima? Dana rekber akan otomatis diteruskan ke seller.','confirmText' => 'Ya, Konfirmasi','cancelText' => 'Batal','type' => 'warning','formId' => 'confirm-product-form-sidebar-desktop-'.e($order->id).'']); ?>
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
                <?php endif; ?>
                
                
                <?php if($order->rating): ?>
                    <?php if($seller): ?>
                    <a href="<?php echo e(route('chat.show', '@' . $seller->username)); ?>" 
                       class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                        Hubungi Penjual
                    </a>
                    <?php endif; ?>
                    
                    <?php if($order->type === 'product' && $order->product): ?>
                    <a href="<?php echo e(route('products.show', $order->product)); ?>" 
                       class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                        Beli Lagi
                    </a>
                    <?php elseif($order->type === 'service' && $order->service): ?>
                    <a href="<?php echo e(route('services.show', $order->service)); ?>" 
                       class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                        Beli Lagi
                    </a>
                    <?php endif; ?>
                    
                    <?php if($order->payment): ?>
                    <a href="<?php echo e(route('orders.show', $order)); ?>#payment" 
                       class="block w-full px-4 py-3 glass hover:bg-white/5 rounded-lg transition-all text-center font-semibold touch-target border border-white/10">
                        Lihat Tagihan
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section: Beri Rating untuk Pesanan Ini (Paling Bawah) -->
    <?php if($order->canBeRated()): ?>
    <div class="mt-8 sm:mt-10">
        <div class="glass p-6 sm:p-8 rounded-xl border-2 border-yellow-500/50 bg-gradient-to-r from-yellow-500/10 via-yellow-500/5 to-transparent">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 rounded-full bg-yellow-500/20 flex items-center justify-center border-2 border-yellow-500/30">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-8 h-8 text-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-8 h-8 text-yellow-400']); ?>
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
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-yellow-400 mb-2 text-xl sm:text-2xl">
                        Beri Rating untuk Pesanan Ini
                    </h3>
                    <p class="text-white/90 mb-4 text-sm sm:text-base">
                        Pesanan Anda telah selesai! Bagikan pengalaman Anda dengan memberikan rating dan ulasan. Ini akan membantu seller lain dalam membuat keputusan.
                    </p>
                    <a href="<?php echo e(route('ratings.create', $order)); ?>" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-yellow-500/20 touch-target">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-5 h-5']); ?>
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
                        <span>Beri Rating Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/orders/show.blade.php ENDPATH**/ ?>