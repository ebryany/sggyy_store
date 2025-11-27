<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'success', 'message' => null]));

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

foreach (array_filter((['type' => 'success', 'message' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $alertConfig = [
        'success' => ['bg' => 'bg-green-500/20', 'border' => 'border-green-500/50', 'text' => 'text-green-400', 'icon' => '✓'],
        'error' => ['bg' => 'bg-red-500/20', 'border' => 'border-red-500/50', 'text' => 'text-red-400', 'icon' => '✕'],
        'warning' => ['bg' => 'bg-yellow-500/20', 'border' => 'border-yellow-500/50', 'text' => 'text-yellow-400', 'icon' => '⚠'],
        'info' => ['bg' => 'bg-blue-500/20', 'border' => 'border-blue-500/50', 'text' => 'text-blue-400', 'icon' => 'ℹ'],
    ];
    $config = $alertConfig[$type] ?? $alertConfig['success'];
?>

<?php
    $hasMessage = false;
    $displayMessage = null;
    
    if ($message) {
        $hasMessage = true;
        $displayMessage = $message;
    } elseif (session($type)) {
        $hasMessage = true;
        $displayMessage = session($type);
    } elseif ($type === 'error' && $errors->any()) {
        // Only show errors in error alert, not in success alert
        $hasMessage = true;
        $displayMessage = $errors->first();
    }
?>

<?php if($hasMessage): ?>
<div x-data="{ show: true }" 
     x-show="show" 
     x-transition 
     class="glass <?php echo e($config['bg']); ?> border <?php echo e($config['border']); ?> rounded-lg p-4 mb-4"
     data-alert-type="<?php echo e($type); ?>"
     data-alert-message="<?php echo e(md5($displayMessage)); ?>">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="<?php echo e($config['text']); ?> text-xl"><?php echo e($config['icon']); ?></span>
            <p class="<?php echo e($config['text']); ?>"><?php echo e($displayMessage); ?></p>
        </div>
        <button x-on:click="show = false" class="<?php echo e($config['text']); ?> hover:opacity-70 touch-target">
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
        </button>
    </div>
</div>
<?php endif; ?>

<script>
// Prevent duplicate alerts on page load
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[data-alert-type][data-alert-message]');
    const seen = new Set();
    
    alerts.forEach(alert => {
        const key = alert.getAttribute('data-alert-type') + '-' + alert.getAttribute('data-alert-message');
        if (seen.has(key)) {
            alert.remove();
        } else {
            seen.add(key);
        }
    });
});
</script>




<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/alert.blade.php ENDPATH**/ ?>