<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

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

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cart = session('cart', []);
    $cartKey = 'product_' . $product->id;
    $isInCart = isset($cart[$cartKey]);
?>

<div class="group relative h-full flex flex-col rounded-xl overflow-hidden bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 hover:border-primary/30 transition-all duration-300 hover:shadow-lg hover:shadow-primary/10"
     x-data="{ 
         isInCart: <?php echo e($isInCart ? 'true' : 'false'); ?>,
         loading: false,
         addToCart() {
             if (this.loading || this.isInCart) return;
             this.loading = true;
             
             fetch('<?php echo e(route('cart.add')); ?>', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({
                     type: 'product',
                     id: <?php echo e($product->id); ?>

                 })
             })
             .then(response => response.json())
             .then(data => {
                 this.loading = false;
                 if (data.success || !data.error) {
                     this.isInCart = true;
                     window.dispatchEvent(new CustomEvent('toast', { 
                         detail: { 
                             message: data.message || 'Produk berhasil ditambahkan ke keranjang', 
                             type: 'success' 
                         } 
                     }));
                     window.dispatchEvent(new CustomEvent('cart-updated', { 
                         detail: { 
                             cartCount: data.cart_count !== undefined ? data.cart_count : null
                         } 
                     }));
                 } else {
                     window.dispatchEvent(new CustomEvent('toast', { 
                         detail: { 
                             message: data.message || data.error || 'Gagal menambahkan ke keranjang', 
                             type: 'error' 
                         } 
                     }));
                 }
             })
             .catch(error => {
                 this.loading = false;
                 window.dispatchEvent(new CustomEvent('toast', { 
                     detail: { 
                         message: 'Terjadi kesalahan. Silakan coba lagi.', 
                         type: 'error' 
                     } 
                 }));
             });
         }
     }">
    <a href="<?php echo e(route('products.show', $product->slug ?: $product->id)); ?>" class="flex flex-col h-full">
        <!-- Image Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5">
            <?php if($product->image): ?>
            <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                 alt="<?php echo e($product->title); ?>" 
                 class="w-full h-48 sm:h-56 object-cover group-hover:scale-110 transition-transform duration-500">
            <?php else: ?>
            <div class="w-full h-48 sm:h-56 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-primary/20 flex items-center justify-center mx-auto mb-3 border border-primary/30">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-8 h-8 sm:w-10 sm:h-10 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-8 h-8 sm:w-10 sm:h-10 text-primary']); ?>
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
                    <p class="text-primary font-bold text-lg sm:text-xl uppercase tracking-wider">
                        <?php
                            $words = explode(' ', $product->title);
                            $initials = '';
                            foreach ($words as $word) {
                                if (strlen($initials) < 3 && !empty($word)) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            }
                            if (empty($initials)) {
                                $initials = 'PROD';
                            }
                        ?>
                        <?php echo e($initials); ?>

                    </p>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Stock Badge -->
            <?php if(!$product->isInStock()): ?>
            <div class="absolute top-3 right-3 px-3 py-1.5 bg-red-500/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-white border border-red-400/50">
                Stok Habis
            </div>
            <?php endif; ?>
            
            <!-- Overlay Gradient on Hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        
        <!-- Content Section -->
        <div class="flex-1 flex flex-col p-4 sm:p-5">
            <!-- Title -->
            <h3 class="text-base sm:text-lg font-bold mb-2 line-clamp-2 text-white group-hover:text-primary transition-colors">
                <?php echo e($product->title); ?>

            </h3>
            
            <!-- Description -->
            <p class="text-white/60 text-xs sm:text-sm mb-4 line-clamp-2 flex-1 leading-relaxed">
                <?php echo e($product->description); ?>

            </p>
            
            <!-- Price & Rating -->
            <div class="mb-4 pb-4 border-b border-white/10">
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-xl sm:text-2xl font-bold text-primary">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                </div>
                <?php if($product->ratings->count() > 0): ?>
                <div class="flex items-center gap-1.5">
                    <div class="flex items-center">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4 text-yellow-400 fill-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4 text-yellow-400 fill-yellow-400']); ?>
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
                        <span class="text-xs sm:text-sm font-semibold text-white/90 ml-1"><?php echo e(number_format($product->averageRating(), 1)); ?></span>
                    </div>
                    <span class="text-xs text-white/50">•</span>
                    <span class="text-xs sm:text-sm text-white/60"><?php echo e($product->ratings->count()); ?> ulasan</span>
                </div>
                <?php else: ?>
                <div class="flex items-center gap-1.5">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/30']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/30']); ?>
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
                    <span class="text-xs sm:text-sm text-white/50">Belum ada rating</span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Footer Info -->
            <div class="flex items-center justify-between text-xs sm:text-sm">
                <div class="flex items-center gap-1.5 text-white/70">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'tag','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'tag','class' => 'w-3.5 h-3.5 sm:w-4 sm:h-4']); ?>
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
                    <span class="truncate"><?php echo e($product->category); ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <?php if($product->isInStock()): ?>
                    <span class="text-green-400 font-medium">Stok: <?php echo e($product->stock); ?></span>
                    <?php endif; ?>
                    <?php if($product->isInStock()): ?>
                    <button type="button"
                            @click.stop="addToCart()"
                            :disabled="loading || isInCart"
                            class="p-2 rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                            :class="isInCart ? 'bg-green-500/20 hover:bg-green-500/30 border border-green-500/30' : 'bg-primary hover:bg-primary-dark border border-primary/30'"
                            title="<?php echo e($isInCart ? 'Sudah di keranjang' : 'Tambah ke keranjang'); ?>">
                        <div x-show="loading" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'cart-plus','xShow' => '!loading','class' => 'w-4 h-4 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'cart-plus','x-show' => '!loading','class' => 'w-4 h-4 text-white']); ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </a>
</div>
<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/product-card.blade.php ENDPATH**/ ?>