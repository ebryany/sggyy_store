<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $statusConfig = [
        'pending' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/30', 'label' => 'Pending'],
        'paid' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'Paid'],
        'processing' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30', 'label' => 'Processing'],
        'completed' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/30', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/30', 'label' => 'Cancelled'],
        'needs_revision' => ['bg' => 'bg-orange-500/20', 'text' => 'text-orange-400', 'border' => 'border-orange-500/30', 'label' => 'Perlu Revisi'],
    ];
    
    $config = $statusConfig[$status] ?? ['bg' => 'bg-white/10', 'text' => 'text-white/60', 'border' => 'border-white/20', 'label' => ucfirst($status ?? 'Unknown')];
?>

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium <?php echo e($config['bg']); ?> <?php echo e($config['text']); ?> border <?php echo e($config['border']); ?>">
    <?php echo e($config['label']); ?>

</span>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/order-status-badge.blade.php ENDPATH**/ ?>