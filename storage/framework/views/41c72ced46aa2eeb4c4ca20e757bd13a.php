

<?php $__env->startSection('title', 'Detail Pesanan - Seller Dashboard - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="mb-4 sm:mb-6">
        <a href="<?php echo e(route('seller.orders.index')); ?>" class="text-primary hover:underline flex items-center space-x-2 touch-target text-sm sm:text-base">
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
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Order Info -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-start gap-3 sm:gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold mb-2 break-words">Order #<?php echo e($order->order_number); ?></h1>
                        <p class="text-white/60 text-sm sm:text-base">Dibuat pada <?php echo e($order->created_at->format('d M Y, H:i')); ?></p>
                    </div>
                    <div class="flex-shrink-0">
                        <?php echo $__env->make('components.order-status-badge', ['status' => $order->status], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
                
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3">Detail Item</h3>
                    <div class="flex items-center space-x-4">
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
                
                <!-- Buyer Info -->
                <?php if($order->user): ?>
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-3 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'user','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user','class' => 'w-5 h-5 text-primary']); ?>
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
                        Informasi Buyer
                    </h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-white/60">Nama</span>
                            <span class="font-semibold"><?php echo e($order->user->name); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-white/60">Email</span>
                            <span class="font-semibold"><?php echo e($order->user->email); ?></span>
                        </div>
                        <?php if($order->user->phone): ?>
                        <div class="flex justify-between">
                            <span class="text-white/60">Telepon</span>
                            <span class="font-semibold"><?php echo e($order->user->phone); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($order->notes): ?>
                <div class="border-t border-white/10 pt-4 mt-4">
                    <h3 class="font-semibold mb-2">Catatan dari Buyer</h3>
                    <p class="text-white/70"><?php echo e($order->notes); ?></p>
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
                        File Tugas dari Buyer
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
                                <p class="font-semibold"><?php echo e($taskFileName); ?></p>
                                <p class="text-xs text-white/60">File tugas dari buyer</p>
                            </div>
                        </div>
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
                <?php if($order->type === 'service' && $order->deliverable_path): ?>
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
                                <p class="font-semibold"><?php echo e($deliverableFileName); ?></p>
                                <p class="text-xs text-white/60">File hasil pekerjaan yang telah diupload</p>
                            </div>
                        </div>
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
                                    onclick="document.querySelector('input[name=deliverable]')?.click()"
                                    class="px-4 py-2 glass glass-hover rounded-lg text-primary">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'edit','class' => 'w-4 h-4 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit','class' => 'w-4 h-4 inline mr-1']); ?>
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
                                Ganti File
                            </button>
                        </div>
                        <p class="text-xs text-white/60 text-center mt-2">
                            <span class="flex items-center justify-center gap-1">
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
                        <p class="text-sm text-yellow-300/80">File hasil pekerjaan tidak dapat ditemukan di storage. Silakan upload ulang.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Payment Info -->
            <?php if($order->payment): ?>
            <div class="glass p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-4">Informasi Pembayaran</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-white/60">Metode Pembayaran</span>
                        <span class="font-semibold"><?php echo e($order->payment->getMethodDisplayName()); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Status Pembayaran</span>
                        <?php if($order->payment->status === 'verified'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">Verified</span>
                        <?php elseif($order->payment->status === 'pending'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-400">Pending</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400">Rejected</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($order->payment->verified_at): ?>
                    <div class="flex justify-between">
                        <span class="text-white/60">Diverifikasi pada</span>
                        <span><?php echo e($order->payment->verified_at->format('d M Y, H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($order->payment->proof_path): ?>
                    <div class="mt-4">
                        <p class="text-white/60 mb-2">Bukti Pembayaran dari Buyer</p>
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
                                 title="Klik untuk membuka di tab baru">
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
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Order Management Controls (Seller) -->
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
            
            <!-- Upload Deliverable Section (Always visible for service orders) -->
            <?php
                $isServiceOrder = $order->type === 'service';
                $isOrderSeller = ($order->service && $order->service->user_id === auth()->id()) || 
                                 ($order->product && $order->product->user_id === auth()->id());
                $canUploadDeliverable = $isServiceOrder && $isOrderSeller && 
                                       in_array($order->status, ['processing', 'waiting_confirmation', 'needs_revision', 'paid']);
            ?>
            
            <?php if($canUploadDeliverable): ?>
            <div class="glass p-4 sm:p-6 rounded-lg border-2 border-primary/30 <?php echo e(in_array($order->status, ['waiting_confirmation', 'needs_revision']) ? 'bg-orange-500/5 border-orange-500/30' : ''); ?>">
                <h3 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-6 h-6 text-primary']); ?>
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
                    Upload Hasil Pekerjaan
                    <?php if(in_array($order->status, ['waiting_confirmation', 'needs_revision'])): ?>
                        <span class="ml-2 px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded border border-orange-500/30 font-semibold">⚠️ Revisi Diperlukan</span>
                    <?php endif; ?>
                </h3>
                
                <?php if(in_array($order->status, ['waiting_confirmation', 'needs_revision'])): ?>
                <div class="mb-4 p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
                    <p class="text-sm text-orange-300 flex items-center gap-2">
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
                        Buyer meminta revisi. Silakan upload hasil pekerjaan yang sudah diperbaiki.
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if($order->deliverable_path): ?>
                <div class="glass p-3 rounded-lg mb-4 bg-green-500/10 border border-green-500/30">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
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
                            <div>
                                <p class="font-semibold text-sm text-green-400">File sudah diupload</p>
                                <p class="text-xs text-white/60"><?php echo e(basename($order->deliverable_path)); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('orders.downloadDeliverable', $order)); ?>" 
                           target="_blank"
                           class="px-3 py-1 bg-green-500/20 text-green-400 hover:bg-green-500/30 rounded text-xs font-semibold transition-colors">
                            📥 Download
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo e(route('orders.uploadDeliverable', $order)); ?>" 
                      enctype="multipart/form-data"
                      x-data="{ uploading: false, fileName: '' }"
                      @submit.prevent="
                          uploading = true;
                          const formData = new FormData($el);
                          fetch('<?php echo e(route('orders.uploadDeliverable', $order)); ?>', {
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
                    <?php echo csrf_field(); ?>
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
                            <?php if($order->deliverable_path): ?>
                            <p class="text-xs text-yellow-400 mt-2">
                                <span class="flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-3 h-3']); ?>
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
                                    File hasil pekerjaan sudah ada. Upload file baru akan mengganti file yang lama.
                                </span>
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" 
                                :disabled="uploading"
                                class="w-full px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold disabled:opacity-50 text-sm cursor-pointer">
                            <span x-show="!uploading" class="flex items-center justify-center gap-2">
                                <?php if($order->deliverable_path): ?>
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'refresh','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'refresh','class' => 'w-4 h-4']); ?>
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
                                    🔄 Update Hasil Pekerjaan
                                <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'upload','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'upload','class' => 'w-4 h-4']); ?>
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
                                    📤 Upload Hasil Pekerjaan
                                <?php endif; ?>
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
            <?php endif; ?>
        </div>
        
        <!-- Sidebar: Timeline -->
        <div class="lg:col-span-1">
            <div class="glass p-6 rounded-lg sticky top-20">
                <h2 class="text-xl font-semibold mb-4">Timeline Pesanan</h2>
                <?php echo $__env->make('components.order-timeline', ['timeline' => $timeline], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('seller.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/seller/orders/show.blade.php ENDPATH**/ ?>