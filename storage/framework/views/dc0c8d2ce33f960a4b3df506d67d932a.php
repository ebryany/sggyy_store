<div class="space-y-3 sm:space-y-4">
    <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="flex items-start space-x-3 sm:space-x-4">
        <!-- Timeline Line -->
        <div class="flex flex-col items-center flex-shrink-0">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center text-lg sm:text-xl
                <?php echo e($item['status'] === 'completed' ? 'bg-green-500/20 text-green-400' : ''); ?>

                <?php echo e($item['status'] === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : ''); ?>

                <?php echo e($item['status'] === 'processing' ? 'bg-blue-500/20 text-blue-400' : ''); ?>

                <?php echo e($item['status'] === 'rejected' || $item['status'] === 'cancelled' ? 'bg-red-500/20 text-red-400' : ''); ?>">
                <?php echo e($item['icon']); ?>

            </div>
            <?php if($index < count($timeline) - 1): ?>
            <div class="w-0.5 h-8 sm:h-12 bg-white/10 mt-2"></div>
            <?php endif; ?>
        </div>
        
        <!-- Timeline Content -->
        <div class="flex-1 pb-4 sm:pb-6 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-2 mb-1">
                <h4 class="font-semibold text-sm sm:text-base
                    <?php echo e($item['status'] === 'completed' ? 'text-green-400' : ''); ?>

                    <?php echo e($item['status'] === 'pending' ? 'text-yellow-400' : ''); ?>

                    <?php echo e($item['status'] === 'processing' ? 'text-blue-400' : ''); ?>

                    <?php echo e($item['status'] === 'rejected' || $item['status'] === 'cancelled' ? 'text-red-400' : ''); ?>">
                    <?php echo e($item['label']); ?>

                </h4>
                <span class="text-xs text-white/60 flex-shrink-0"><?php echo e($item['time']); ?></span>
            </div>
            <p class="text-xs sm:text-sm text-white/70 break-words"><?php echo e($item['description']); ?></p>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>




<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/order-timeline.blade.php ENDPATH**/ ?>