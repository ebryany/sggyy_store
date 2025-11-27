<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['escrow', 'order']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['escrow', 'order']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $timeline = [];
    
    // Created (hanya tampilkan sekali, tidak duplikat dengan holding)
    if ($escrow->created_at) {
        if ($escrow->isHolding() && $escrow->hold_until) {
            // Jika masih holding, gabungkan info created dan holding dalam satu item
            $timeline[] = [
                'date' => $escrow->created_at,
                'status' => 'created',
                'label' => 'Escrow Dibuat & Dana Ditahan',
                'description' => 'Dana sebesar Rp ' . number_format($escrow->amount, 0, ',', '.') . ' ditahan di escrow. Akan dilepas pada ' . $escrow->hold_until->format('d M Y, H:i'),
                'icon' => 'shield',
                'color' => 'blue',
            ];
        } else {
            // Jika sudah tidak holding, tampilkan created saja
            $timeline[] = [
                'date' => $escrow->created_at,
                'status' => 'created',
                'label' => 'Escrow Dibuat',
                'description' => 'Dana sebesar Rp ' . number_format($escrow->amount, 0, ',', '.') . ' ditahan di escrow',
                'icon' => 'shield',
                'color' => 'blue',
            ];
        }
    }
    
    // Disputed
    if ($escrow->isDisputed() && $escrow->disputed_at) {
        $timeline[] = [
            'date' => $escrow->disputed_at,
            'status' => 'disputed',
            'label' => 'Dispute Dibuat',
            'description' => $escrow->dispute_reason ? Str::limit($escrow->dispute_reason, 100) : 'Dispute dibuat oleh ' . ($escrow->disputedBy ? $escrow->disputedBy->name : 'user'),
            'icon' => 'alert',
            'color' => 'orange',
        ];
    }
    
    // Released
    if ($escrow->isReleased() && $escrow->released_at) {
        $releaseTypeLabels = [
            'early' => 'dilepas lebih awal',
            'auto' => 'dilepas otomatis',
            'manual' => 'dilepas manual oleh admin',
        ];
        $releaseType = $releaseTypeLabels[$escrow->release_type] ?? 'dilepas';
        
        $timeline[] = [
            'date' => $escrow->released_at,
            'status' => 'released',
            'label' => 'Escrow Dilepas',
            'description' => 'Dana sebesar Rp ' . number_format($escrow->seller_earning ?? $escrow->amount, 0, ',', '.') . ' telah ' . $releaseType . ' ke seller',
            'icon' => 'check',
            'color' => 'green',
        ];
    }
    
    // Refunded
    if ($escrow->isRefunded()) {
        $timeline[] = [
            'date' => $escrow->updated_at,
            'status' => 'refunded',
            'label' => 'Dana Dikembalikan',
            'description' => 'Dana sebesar Rp ' . number_format($escrow->amount, 0, ',', '.') . ' telah dikembalikan ke buyer',
            'icon' => 'arrow-left',
            'color' => 'red',
        ];
    }
    
    // Sort by date
    usort($timeline, function($a, $b) {
        return $a['date']->timestamp <=> $b['date']->timestamp;
    });
?>

<?php if(count($timeline) > 0): ?>
<div class="mt-4 pt-4 border-t border-white/10">
    <div class="mb-3">
        <h4 class="text-sm font-semibold mb-1 flex items-center gap-2">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-4 h-4 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-4 h-4 text-primary']); ?>
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
            Timeline Escrow / Rekber
        </h4>
        <p class="text-xs text-white/60">
            Timeline khusus untuk status escrow (perlindungan pembayaran). 
            <strong>Berbeda</strong> dengan Timeline Pesanan yang menampilkan status order.
        </p>
    </div>
    
    <div class="space-y-4">
        <?php $__currentLoopData = $timeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex gap-3">
            <!-- Timeline Line -->
            <div class="flex flex-col items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center 
                    <?php if($item['color'] === 'blue'): ?> bg-blue-500/20 border border-blue-500/30 text-blue-400
                    <?php elseif($item['color'] === 'green'): ?> bg-green-500/20 border border-green-500/30 text-green-400
                    <?php elseif($item['color'] === 'orange'): ?> bg-orange-500/20 border border-orange-500/30 text-orange-400
                    <?php else: ?> bg-red-500/20 border border-red-500/30 text-red-400
                    <?php endif; ?>">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => ''.e($item['icon']).'','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($item['icon']).'','class' => 'w-5 h-5']); ?>
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
                <?php if($index < count($timeline) - 1): ?>
                <div class="w-0.5 h-full min-h-8 bg-white/10 mt-2"></div>
                <?php endif; ?>
            </div>
            
            <!-- Timeline Content -->
            <div class="flex-1 pb-4">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <h5 class="font-semibold text-sm"><?php echo e($item['label']); ?></h5>
                    <span class="text-xs text-white/60 whitespace-nowrap">
                        <?php echo e($item['date']->format('d M Y, H:i')); ?>

                    </span>
                </div>
                <p class="text-xs text-white/70"><?php echo e($item['description']); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/escrow-timeline.blade.php ENDPATH**/ ?>