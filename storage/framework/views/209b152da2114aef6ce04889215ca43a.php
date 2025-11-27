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
    // Add data attributes for real-time updates
    $orderId = $order->id;
    $escrowId = $escrow->id;
?>

<div data-escrow-card data-order-id="<?php echo e($orderId); ?>" data-escrow-id="<?php echo e($escrowId); ?>">

<?php
    $isHolding = $escrow->isHolding();
    $isReleased = $escrow->isReleased();
    $isDisputed = $escrow->isDisputed();
    $isRefunded = $escrow->isRefunded();
    
    $holdUntil = $escrow->hold_until;
    $daysRemaining = $holdUntil ? now()->diffInDays($holdUntil, false) : 0;
    $hoursRemaining = $holdUntil ? now()->diffInHours($holdUntil, false) : 0;
    $progress = $holdUntil ? max(0, min(100, (1 - ($holdUntil->diffInSeconds(now()) / $holdUntil->diffInSeconds($escrow->created_at))) * 100)) : 0;
    
    // Check if using xenPlatform
    $settingsService = app(\App\Services\SettingsService::class);
    $xenditSettings = $settingsService->getXenditSettings();
    $useXenPlatform = $xenditSettings['enable_xenplatform'] ?? false;
    $hasDisbursement = !empty($escrow->xendit_disbursement_id);
?>

<div class="glass p-5 sm:p-6 rounded-xl border border-white/10">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg flex items-center justify-center
                <?php echo e($isHolding ? 'bg-blue-500/20 border border-blue-500/30' : ''); ?>

                <?php echo e($isReleased ? 'bg-green-500/20 border border-green-500/30' : ''); ?>

                <?php echo e($isDisputed ? 'bg-orange-500/20 border border-orange-500/30' : ''); ?>

                <?php echo e($isRefunded ? 'bg-red-500/20 border border-red-500/30' : ''); ?>">
                <?php if($isHolding): ?>
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shield','class' => 'w-6 h-6 text-blue-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield','class' => 'w-6 h-6 text-blue-400']); ?>
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
                <?php elseif($isReleased): ?>
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
                <?php elseif($isDisputed): ?>
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-6 h-6 text-orange-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-6 h-6 text-orange-400']); ?>
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
                <?php elseif($isRefunded): ?>
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-6 h-6 text-red-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-6 h-6 text-red-400']); ?>
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
                <?php endif; ?>
            </div>
            <div>
                <h3 class="font-semibold text-white mb-1">Status Escrow / Rekber</h3>
                <div class="flex items-center gap-2 flex-wrap">
                    <?php if($isHolding): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            Dana Ditahan
                        </span>
                    <?php elseif($isReleased): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                            Dana Dilepas
                        </span>
                    <?php elseif($isDisputed): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30">
                            Refund
                        </span>
                    <?php elseif($isRefunded): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">
                            Dikembalikan
                        </span>
                    <?php endif; ?>
                    <?php if($useXenPlatform): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-400 border border-purple-500/30 flex items-center gap-1">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shield-check','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-3 h-3']); ?>
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
    </div>
    
    <!-- Escrow Timeline -->
    <?php if (isset($component)) { $__componentOriginal3c1bf9d692e5162043bf2eda0fef9339 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c1bf9d692e5162043bf2eda0fef9339 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.escrow-timeline','data' => ['escrow' => $escrow,'order' => $order]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('escrow-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['escrow' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($escrow),'order' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c1bf9d692e5162043bf2eda0fef9339)): ?>
<?php $attributes = $__attributesOriginal3c1bf9d692e5162043bf2eda0fef9339; ?>
<?php unset($__attributesOriginal3c1bf9d692e5162043bf2eda0fef9339); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c1bf9d692e5162043bf2eda0fef9339)): ?>
<?php $component = $__componentOriginal3c1bf9d692e5162043bf2eda0fef9339; ?>
<?php unset($__componentOriginal3c1bf9d692e5162043bf2eda0fef9339); ?>
<?php endif; ?>
    
    <!-- Info: Perbedaan Timeline -->
    <div class="mt-4 p-3 rounded-lg bg-white/5 border border-white/10">
        <p class="text-xs text-white/60 leading-relaxed">
            <strong>Perbedaan Timeline:</strong><br>
            • <strong>Timeline Pesanan</strong> (di sidebar kanan): Menampilkan status order (pending → paid → processing → completed)<br>
            • <strong>Timeline Escrow</strong> (di atas): Menampilkan status escrow/rekber (dibuat → ditahan → dilepas/refund)
        </p>
    </div>
</div>
</div>
    </div>

    <?php if($isHolding): ?>
        <!-- Hold Period Info -->
        <div class="mb-4 p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-white/70">Periode Hold</span>
                <span class="text-sm font-semibold text-blue-400" 
                      x-data="countdownTimer('<?php echo e($holdUntil->toISOString()); ?>')"
                      x-text="timeRemaining">
                    <?php echo e($holdUntil->diffForHumans()); ?>

                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-white/10 rounded-full h-2 mb-2">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                     style="width: <?php echo e($progress); ?>%"></div>
            </div>
            
            <p class="text-xs text-white/60">
                <?php if($daysRemaining > 0): ?>
                    Tersisa <?php echo e($daysRemaining); ?> hari
                <?php elseif($hoursRemaining > 0): ?>
                    Tersisa <?php echo e($hoursRemaining); ?> jam
                <?php else: ?>
                    Akan dilepas segera
                <?php endif; ?>
            </p>
        </div>

        <!-- Escrow Amount Info -->
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-white/70">Total Escrow:</span>
                <span class="font-semibold text-white">Rp <?php echo e(number_format($escrow->amount, 0, ',', '.')); ?></span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-white/70">Komisi Platform:</span>
                <span class="text-white/60">Rp <?php echo e(number_format($escrow->platform_fee, 0, ',', '.')); ?></span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-white/10">
                <span class="text-white/70">Earning Seller:</span>
                <span class="font-semibold text-primary">Rp <?php echo e(number_format($escrow->seller_earning, 0, ',', '.')); ?></span>
            </div>
        </div>

        <!-- Info Box -->
        <div class="p-3 rounded-lg bg-white/5 border border-white/10">
            <p class="text-xs text-white/70 leading-relaxed">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'w-4 h-4 inline text-blue-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'w-4 h-4 inline text-blue-400']); ?>
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
                <?php if($useXenPlatform): ?>
                    Pembayaran menggunakan <strong class="text-purple-400">xenPlatform</strong>. Dana sudah di-split otomatis ke seller sub-account saat pembayaran verified. Escrow ini untuk tracking saja.
                <?php else: ?>
                    Dana ditahan di escrow untuk keamanan transaksi. Dana akan dilepas setelah periode hold selesai atau saat Anda konfirmasi selesai.
                <?php endif; ?>
            </p>
        </div>

        <!-- Action Buttons (for buyer, if order completed) -->
        <?php if($order->status === 'completed' && auth()->id() === $order->user_id): ?>
        <div class="mt-4 pt-4 border-t border-white/10 space-y-3">
            <!-- Early Release Button with Confirmation -->
            <div x-data="{ 
                showConfirm: false,
                confirmed: false,
                submitting: false 
            }">
                <!-- Confirmation Modal -->
                <div x-show="showConfirm" 
                     x-cloak
                     style="display: none;"
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                     @click.self="showConfirm = false">
                    <div class="glass p-6 rounded-xl border border-white/20 max-w-md w-full" @click.stop>
                        <div class="flex items-center gap-3 mb-4">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-8 h-8 text-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-8 h-8 text-yellow-400']); ?>
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
                            <h3 class="text-xl font-semibold">Konfirmasi Lepas Escrow</h3>
                        </div>
                        
                        <div class="mb-6 space-y-3">
                            <p class="text-white/80">
                                Apakah Anda yakin ingin melepas escrow sekarang?
                            </p>
                            <div class="p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/30">
                                <p class="text-sm text-yellow-300">
                                    <strong>Penting:</strong> Dana akan langsung dikirim ke seller. 
                                    Pastikan Anda sudah menerima produk/jasa sesuai pesanan.
                                </p>
                            </div>
                            <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30">
                                <p class="text-sm text-red-300">
                                    <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>
                            
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input type="checkbox" x-model="confirmed" class="mt-1">
                                <span class="text-sm text-white/80">
                                    Saya memahami bahwa dana akan dikirim ke seller dan tindakan ini tidak dapat dibatalkan
                                </span>
                            </label>
                        </div>
                        
                        <div class="flex gap-3">
                            <button 
                                type="button"
                                @click="showConfirm = false; confirmed = false"
                                class="flex-1 px-4 py-2 glass border border-white/20 rounded-lg hover:border-white/40 transition-all"
                            >
                                Batal
                            </button>
                            <form action="<?php echo e(route('orders.confirm', $order)); ?>" method="POST" @submit="submitting = true">
                                <?php echo csrf_field(); ?>
                                <button 
                                    type="submit"
                                    :disabled="submitting || !confirmed"
                                    class="flex-1 px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                                >
                                    <span x-show="!submitting">Ya, Lepas Escrow</span>
                                    <span x-show="submitting" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Trigger Button -->
                <button 
                    type="button"
                    @click="showConfirm = true"
                    class="w-full px-4 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
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
                    Konfirmasi Selesai & Lepas Escrow
                </button>
                <p class="text-xs text-white/60 mt-2 text-center">
                    Konfirmasi selesai akan melepas escrow segera ke seller
                </p>
            </div>
            
            <!-- Dispute Button -->
            <?php if($escrow->canBeDisputed()): ?>
            <a 
                href="<?php echo e(route('disputes.create', $order)); ?>" 
                class="block w-full px-4 py-3 bg-orange-500/20 hover:bg-orange-500/30 border border-orange-500/30 hover:border-orange-500/50 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2 text-orange-400"
            >
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
                Ajukan Refund
            </a>
            <p class="text-xs text-white/60 text-center">
                Jika ada masalah dengan pesanan, Anda bisa mengajukan refund
            </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php elseif($isReleased): ?>
        <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/20">
            <div class="flex items-center gap-2 mb-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-5 h-5 text-green-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-5 h-5 text-green-400']); ?>
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
                <span class="font-semibold text-green-400">Escrow Telah Dilepas</span>
            </div>
            <p class="text-sm text-white/70 mb-2">
                <?php if($useXenPlatform && $hasDisbursement): ?>
                    Dana telah di-disburse ke seller sub-account via Xendit xenPlatform.
                <?php elseif($escrow->release_type === 'early'): ?>
                    Dilepas lebih awal saat Anda konfirmasi selesai
                <?php elseif($escrow->release_type === 'auto'): ?>
                    Dilepas otomatis setelah periode hold selesai
                <?php else: ?>
                    Dilepas secara manual oleh admin
                <?php endif; ?>
            </p>
            <?php if($useXenPlatform && $hasDisbursement): ?>
                <p class="text-xs text-purple-400 mb-2 flex items-center gap-1">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shield-check','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-3 h-3']); ?>
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
                    Disbursement ID: <?php echo e(substr($escrow->xendit_disbursement_id, 0, 20)); ?>...
                </p>
            <?php endif; ?>
            <p class="text-xs text-white/60">
                Pada: <?php echo e($escrow->released_at->format('d M Y, H:i')); ?>

            </p>
        </div>

    <?php elseif($isDisputed): ?>
        <div class="p-4 rounded-lg bg-orange-500/10 border border-orange-500/20">
            <div class="flex items-center gap-2 mb-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-5 h-5 text-orange-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-5 h-5 text-orange-400']); ?>
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
                <span class="font-semibold text-orange-400">Escrow Sedang Refund</span>
            </div>
            <p class="text-sm text-white/70 mb-2">
                <?php echo e($escrow->dispute_reason ?? 'Escrow sedang dalam proses refund'); ?>

            </p>
            <p class="text-xs text-white/60">
                Refund diajukan pada: <?php echo e($escrow->disputed_at->format('d M Y, H:i')); ?>

            </p>
            <p class="text-xs text-white/60 mt-2">
                Admin akan meninjau dan menyelesaikan refund ini.
            </p>
        </div>

    <?php elseif($isRefunded): ?>
        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <div class="flex items-center gap-2 mb-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-5 h-5 text-red-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5 text-red-400']); ?>
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
                <span class="font-semibold text-red-400">Escrow Dikembalikan</span>
            </div>
            <p class="text-sm text-white/70">
                Dana telah dikembalikan ke wallet Anda.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
function countdownTimer(targetDate) {
    return {
        timeRemaining: '',
        init() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = new Date();
            const target = new Date(targetDate);
            const diff = target - now;
            
            if (diff <= 0) {
                this.timeRemaining = 'Selesai';
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            
            if (days > 0) {
                this.timeRemaining = `${days} hari ${hours} jam`;
            } else if (hours > 0) {
                this.timeRemaining = `${hours} jam ${minutes} menit`;
            } else {
                this.timeRemaining = `${minutes} menit`;
            }
        }
    };
}
</script>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/escrow-status-card.blade.php ENDPATH**/ ?>