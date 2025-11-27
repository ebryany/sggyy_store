<?php
    // Determine completed stages
    $completedStages = [];
    foreach ($timeline as $index => $item) {
        if ($item['status'] === 'completed') {
            $completedStages[] = $index;
        }
    }
    $lastCompletedIndex = count($completedStages) > 0 ? max($completedStages) : -1;
?>

<div class="w-full overflow-x-auto pb-2">
    <div class="relative flex items-start justify-between min-w-max px-2">
        <!-- Connecting Line (full width) -->
        <div class="absolute top-6 left-0 right-0 h-0.5 z-0" style="margin: 0 24px;">
            <div class="h-full w-full bg-white/20"></div>
            <?php if($lastCompletedIndex >= 0): ?>
            <div class="absolute top-0 left-0 h-full bg-primary transition-all duration-300" 
                 style="width: <?php echo e((($lastCompletedIndex + 1) / (count($timeline) - 1)) * 100); ?>%;">
            </div>
            <?php endif; ?>
        </div>
        
        <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex flex-col items-center flex-shrink-0 relative z-10" style="flex: 1; max-width: <?php echo e(100 / count($timeline)); ?>%;">
            
            <!-- Icon Circle -->
            <div class="relative z-10 w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold border-2 transition-all
                <?php echo e($index <= $lastCompletedIndex ? 'bg-primary border-primary' : ($index === $lastCompletedIndex + 1 ? 'bg-primary/20 border-primary' : 'bg-white/10 border-white/20')); ?>">
                <?php
                    $iconType = $item['icon'] ?? 'default';
                    $isCompleted = $index <= $lastCompletedIndex;
                    $isCurrent = $index === $lastCompletedIndex + 1;
                ?>
                
                <?php if($isCompleted): ?>
                    <!-- Completed: Show checkmark -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                <?php elseif($isCurrent): ?>
                    <!-- Current/Pending: Show appropriate icon based on label -->
                    <?php if(str_contains(strtolower($item['label']), 'dibuat') || $iconType === 'document'): ?>
                        <!-- Document icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    <?php elseif(str_contains(strtolower($item['label']), 'dibayarkan') || str_contains(strtolower($item['label']), 'bayar') || $iconType === 'money'): ?>
                        <!-- Money/Banknotes icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    <?php elseif(str_contains(strtolower($item['label']), 'dikirimkan') || str_contains(strtolower($item['label']), 'kirim') || $iconType === 'truck'): ?>
                        <!-- Truck icon (Shopee style) -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    <?php elseif(str_contains(strtolower($item['label']), 'selesai') || $iconType === 'box'): ?>
                        <!-- Box with arrow down icon (Shopee style) -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V9m0 0l-3 3m3-3l3 3"/>
                        </svg>
                    <?php elseif(str_contains(strtolower($item['label']), 'dinilai') || $iconType === 'star'): ?>
                        <!-- Star icon (solid) -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    <?php else: ?>
                        <!-- Default clock icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Future: Show empty/gray icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" opacity="0.3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                <?php endif; ?>
            </div>
            
            <!-- Label & Time -->
            <div class="mt-3 text-center w-full px-1">
                <p class="text-xs sm:text-sm font-medium mb-1 leading-tight
                    <?php echo e($index <= $lastCompletedIndex ? 'text-white' : ($index === $lastCompletedIndex + 1 ? 'text-white' : 'text-white/60')); ?>">
                    <?php echo e($item['label']); ?>

                </p>
                <?php if(!empty($item['time'])): ?>
                <p class="text-xs text-white/50 mb-0.5">
                    <?php echo e($item['time']); ?>

                </p>
                <?php endif; ?>
                <?php if(isset($item['amount'])): ?>
                <p class="text-xs text-white/60">
                    (<?php echo e($item['amount']); ?>)
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/order-timeline.blade.php ENDPATH**/ ?>