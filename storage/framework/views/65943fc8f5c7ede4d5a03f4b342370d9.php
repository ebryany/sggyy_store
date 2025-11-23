<?php $__env->startSection('title', $product->title . ' - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary/10 via-transparent to-transparent py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm text-white/60">
                <a href="<?php echo e(route('home')); ?>" class="hover:text-primary transition-colors">Beranda</a>
                <span class="mx-2">/</span>
                <a href="<?php echo e(route('products.index')); ?>" class="hover:text-primary transition-colors">Produk</a>
                <span class="mx-2">/</span>
                <span class="text-white/90"><?php echo e(Str::limit($product->title, 30)); ?></span>
            </nav>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Image Section -->
                <div class="relative">
                    <?php if($product->image): ?>
                    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                        <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                             alt="<?php echo e($product->title); ?>" 
                             class="w-full h-64 sm:h-80 lg:h-96 object-cover">
                    </div>
                    <?php else: ?>
                    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 h-64 sm:h-80 lg:h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-primary/20 flex items-center justify-center mx-auto mb-4 border border-primary/30">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-12 h-12 sm:w-14 sm:h-14 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-12 h-12 sm:w-14 sm:h-14 text-primary']); ?>
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
                            <p class="text-primary font-bold text-xl sm:text-2xl uppercase tracking-wider">
                                <?php echo e(Str::limit($product->title, 3)); ?>

                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Stock Badge -->
                    <?php if(!$product->isInStock()): ?>
                    <div class="absolute top-4 right-4 px-4 py-2 bg-red-500/90 backdrop-blur-sm rounded-lg text-sm font-semibold text-white border border-red-400/50">
                        Stok Habis
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Info Section -->
                <div class="space-y-6">
                    <!-- Title & Price -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 text-white leading-tight">
                            <?php echo e($product->title); ?>

                        </h1>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl lg:text-5xl font-bold text-primary">
                                    Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <?php if($product->ratings->count() > 0): ?>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-5 h-5 text-yellow-400 fill-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-5 h-5 text-yellow-400 fill-yellow-400']); ?>
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
                                    <span class="font-bold text-lg ml-1"><?php echo e(number_format($product->averageRating(), 1)); ?></span>
                                </div>
                                <span class="text-white/60 text-sm">(<?php echo e($product->ratings->count()); ?> ulasan)</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-6">
                            <span class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 flex items-center gap-2 text-sm">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'tag','class' => 'w-4 h-4 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'tag','class' => 'w-4 h-4 text-primary']); ?>
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
                                <span><?php echo e($product->category); ?></span>
                            </span>
                            <?php if($product->isInStock()): ?>
                            <span class="px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 text-sm font-medium">Stok: <?php echo e($product->stock); ?></span>
                            <?php else: ?>
                            <span class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 text-sm font-medium">Stok Habis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="p-5 sm:p-6 rounded-xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 mb-6">
                        <h2 class="text-lg font-semibold mb-3 text-white">Deskripsi</h2>
                        <p class="text-white/80 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                            <?php echo e($product->description); ?>

                        </p>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'eye','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'eye','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']); ?>
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
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1"><?php echo e(number_format($product->views_count)); ?></div>
                            <div class="text-xs sm:text-sm text-white/60">Dilihat</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shopping-bag','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-bag','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']); ?>
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
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1"><?php echo e(number_format($product->sold_count)); ?></div>
                            <div class="text-xs sm:text-sm text-white/60">Terjual</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shield','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5']); ?>
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
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1"><?php echo e($product->warranty_days); ?></div>
                            <div class="text-xs sm:text-sm text-white/60">Garansi</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2.5']); ?>
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
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1">
                                <?php echo e($product->ratings->count() > 0 ? number_format($product->averageRating(), 1) : '-'); ?>

                            </div>
                            <div class="text-xs sm:text-sm text-white/60">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column: Order Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Form Card -->
                <?php if($product->isInStock()): ?>
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-white">Beli Produk Ini</h2>
                    
                    <form method="POST" action="<?php echo e(route('checkout.store')); ?>" 
                          x-data="{ loading: false, paymentMethod: 'wallet' }"
                          @submit.prevent="
                              loading = true;
                              fetch($el.action, {
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/json',
                                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                      'Accept': 'application/json'
                                  },
                                  body: JSON.stringify({
                                      type: 'product',
                                      product_id: <?php echo e($product->id); ?>,
                                      payment_method: paymentMethod
                                  })
                              })
                              .then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Pesanan berhasil dibuat', type: 'success' } }));
                                      setTimeout(() => window.location.href = data.redirect || '<?php echo e(route('orders.index')); ?>', 1000);
                                  } else {
                                      window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || data.error || 'Terjadi kesalahan', type: 'error' } }));
                                      loading = false;
                                  }
                              })
                              .catch(error => {
                                  window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Terjadi kesalahan. Silakan coba lagi.', type: 'error' } }));
                                  loading = false;
                              });
                          ">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="type" value="product">
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-3 text-white">Metode Pembayaran</label>
                            <div class="space-y-3">
                                <?php if(isset($featureFlags) && $featureFlags['enable_wallet']): ?>
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'wallet' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="wallet" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'dollar','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'dollar','class' => 'w-5 h-5 text-primary']); ?>
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
                                            Saldo Wallet
                                        </div>
                                        <div class="text-xs text-white/60">Bayar langsung dari saldo</div>
                                    </div>
                                </label>
                                <?php endif; ?>
                                <?php if(isset($featureFlags) && $featureFlags['enable_bank_transfer']): ?>
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'bank_transfer' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-5 h-5 text-primary']); ?>
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
                                            Transfer Bank
                                        </div>
                                        <div class="text-xs text-white/60">Upload bukti transfer</div>
                                    </div>
                                </label>
                                <?php endif; ?>
                                <?php if(isset($featureFlags) && $featureFlags['enable_qris']): ?>
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'qris' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mobile','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','class' => 'w-5 h-5 text-primary']); ?>
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
                                            QRIS
                                        </div>
                                        <div class="text-xs text-white/60">Scan QR code untuk bayar</div>
                                    </div>
                                </label>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Info -->
                        <div x-show="paymentMethod === 'bank_transfer'" 
                             x-transition
                             class="mb-6 p-5 rounded-xl bg-blue-500/10 border-2 border-blue-500/30">
                            <?php if(isset($bankAccountInfo) && ($bankAccountInfo['bank_name'] || $bankAccountInfo['bank_account_number'])): ?>
                            <h3 class="font-semibold mb-4 text-blue-400 flex items-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-5 h-5']); ?>
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
                                Transfer ke Rekening Berikut
                            </h3>
                            <div class="space-y-2 text-sm">
                                <?php if($bankAccountInfo['bank_name']): ?>
                                <div class="flex justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60">Bank:</span>
                                    <span class="font-semibold text-white"><?php echo e($bankAccountInfo['bank_name']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($bankAccountInfo['bank_account_number']): ?>
                                <div class="flex justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60">No. Rekening:</span>
                                    <span class="font-semibold font-mono text-white"><?php echo e($bankAccountInfo['bank_account_number']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if($bankAccountInfo['bank_account_name']): ?>
                                <div class="flex justify-between py-2">
                                    <span class="text-white/60">Atas Nama:</span>
                                    <span class="font-semibold text-white"><?php echo e($bankAccountInfo['bank_account_name']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-white/60 mt-4 flex items-center gap-1">
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
                                Upload bukti transfer setelah checkout
                            </p>
                            <?php else: ?>
                            <p class="text-yellow-400 text-sm flex items-center gap-1">
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
                                Informasi rekening bank belum dikonfigurasi. Silakan hubungi admin.
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- QRIS Info -->
                        <div x-show="paymentMethod === 'qris'" 
                             x-transition
                             class="mb-6 p-5 rounded-xl bg-green-500/10 border-2 border-green-500/30">
                            <?php if(isset($bankAccountInfo) && $bankAccountInfo['qris_code']): ?>
                            <h3 class="font-semibold mb-4 text-green-400 flex items-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mobile','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','class' => 'w-5 h-5']); ?>
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
                                Scan QRIS Berikut
                            </h3>
                            <?php if(filter_var($bankAccountInfo['qris_code'], FILTER_VALIDATE_URL)): ?>
                            <img src="<?php echo e($bankAccountInfo['qris_code']); ?>" 
                                 alt="QRIS Code" 
                                 class="w-full max-w-xs mx-auto rounded-lg mb-4">
                            <?php elseif(str_starts_with($bankAccountInfo['qris_code'], 'data:image')): ?>
                            <img src="<?php echo e($bankAccountInfo['qris_code']); ?>" 
                                 alt="QRIS Code" 
                                 class="w-full max-w-xs mx-auto rounded-lg mb-4">
                            <?php else: ?>
                            <div class="bg-white p-4 rounded-lg mb-4 flex justify-center">
                                <div id="qris-code-product" class="w-full max-w-xs"></div>
                            </div>
                            <script>
                                document.getElementById('qris-code-product').innerHTML = '<img src="data:image/png;base64,<?php echo e($bankAccountInfo['qris_code']); ?>" alt="QRIS" class="w-full">';
                            </script>
                            <?php endif; ?>
                            <p class="text-xs text-white/60 mt-4 flex items-center gap-1">
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
                                Upload bukti pembayaran setelah checkout
                            </p>
                            <?php else: ?>
                            <p class="text-yellow-400 text-sm flex items-center gap-1">
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
                                QRIS code belum dikonfigurasi. Silakan hubungi admin.
                            </p>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full px-6 py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-lg">
                            <span x-show="!loading" class="flex items-center justify-center gap-2">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shopping-cart','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-5 h-5']); ?>
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
                                Beli Sekarang
                            </span>
                            <span x-show="loading" class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                </div>
                <?php else: ?>
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8 text-center">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-16 h-16 text-white/40 mx-auto mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-16 h-16 text-white/40 mx-auto mb-4']); ?>
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
                    <h3 class="text-xl font-semibold mb-2 text-white">Produk Tidak Tersedia</h3>
                    <p class="text-white/60">Stok produk ini sedang habis.</p>
                </div>
                <?php endif; ?>
                
                <!-- Reviews Section -->
                <?php if($product->ratings->count() > 0): ?>
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-white">Ulasan (<?php echo e($product->ratings->count()); ?>)</h2>
                    <div class="space-y-6">
                        <?php $__currentLoopData = $product->ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="pb-6 border-b border-white/10 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                <img src="<?php echo e($rating->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($rating->user->name)); ?>" 
                                     alt="<?php echo e($rating->user->name); ?>" 
                                     class="w-12 h-12 rounded-full border-2 border-white/10">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-white"><?php echo e($rating->user->name); ?></p>
                                            <div class="flex items-center gap-1 mt-1">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'star','class' => 'w-4 h-4 '.e($i <= $rating->rating ? 'text-yellow-400 fill-yellow-400' : 'text-white/20').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'star','class' => 'w-4 h-4 '.e($i <= $rating->rating ? 'text-yellow-400 fill-yellow-400' : 'text-white/20').'']); ?>
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
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <span class="text-white/60 text-sm"><?php echo e($rating->created_at->format('d M Y')); ?></span>
                                    </div>
                                    <?php if($rating->comment): ?>
                                    <p class="text-white/70 mt-2 leading-relaxed"><?php echo e($rating->comment); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Right Column: Seller Info -->
            <div class="space-y-6">
                <!-- Seller Card -->
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6">
                    <h3 class="font-semibold mb-5 text-white text-lg">Dijual oleh</h3>
                    
                    <?php if($product->user->store_slug): ?>
                    <a href="<?php echo e(route('store.show', $product->user->store_slug)); ?>" class="block group">
                    <?php endif; ?>
                        <div class="flex items-start gap-4 mb-5">
                            <div class="relative flex-shrink-0">
                                <img src="<?php echo e($product->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->user->name)); ?>" 
                                     alt="<?php echo e($product->user->name); ?>" 
                                     class="w-16 h-16 rounded-full border-2 border-white/10 group-hover:border-primary transition-colors">
                                <?php if($product->user->updated_at && $product->user->updated_at->gt(now()->subMinutes(5))): ?>
                                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-dark rounded-full"></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-lg truncate group-hover:text-primary transition-colors"><?php echo e($product->user->name); ?></p>
                                    <?php if($product->user->isSeller() || $product->user->isAdmin()): ?>
                                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php endif; ?>
                                </div>
                                <?php
                                    $sellerRating = $product->user->products()->whereHas('ratings')->with('ratings')->get()->pluck('ratings')->flatten();
                                    $avgRating = $sellerRating->avg('rating') ?? 0;
                                    $totalSold = $product->user->products()->sum('sold_count');
                                ?>
                                <?php if($avgRating > 0): ?>
                                <div class="flex items-center gap-2 text-sm mb-2">
                                    <span class="font-semibold text-yellow-400"><?php echo e(number_format($avgRating, 1)); ?></span>
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-3 h-3 <?php echo e($i <= round($avgRating) ? 'text-yellow-400' : 'text-white/20'); ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-white/60">(<?php echo e($totalSold); ?> terjual)</span>
                                </div>
                                <?php endif; ?>
                                <?php if($product->user->store_slug): ?>
                                <p class="text-xs text-white/40 group-hover:text-primary/60 transition-colors">Klik untuk melihat toko</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php if($product->user->store_slug): ?>
                    </a>
                    <?php endif; ?>
                    
                    <!-- Chat Button -->
                    <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->id() !== $product->user_id): ?>
                    <a href="<?php echo e(route('chat.show', $product->user->id)); ?>" class="block w-full px-4 py-3 rounded-lg border-2 border-green-500/30 bg-green-500/20 text-green-400 hover:bg-green-500/30 hover:border-green-500/50 transition-all font-semibold mb-5 flex items-center justify-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chat','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chat','class' => 'w-5 h-5']); ?>
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
                        Chat Sekarang
                    </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    
                    <!-- Seller Stats -->
                    <div class="space-y-3 text-sm border-t border-white/10 pt-5">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60">Produk</span>
                            <span class="font-semibold text-white"><?php echo e($product->user->products()->count()); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60">Bergabung</span>
                            <span class="font-semibold text-white"><?php echo e($product->user->created_at->format('d M Y')); ?></span>
                        </div>
                    </div>
                    
                    <?php if($product->user->isSeller() && $product->user->store_slug): ?>
                    <div class="mt-5 pt-5 border-t border-white/10">
                        <a href="<?php echo e(route('store.show', $product->user->store_slug)); ?>" class="text-primary hover:text-primary-dark text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'w-4 h-4']); ?>
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
                            Kunjungi Toko
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Edit/Delete Actions -->
                <?php if(auth()->check() && (auth()->id() === $product->user_id || auth()->user()->isAdmin())): ?>
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6">
                    <h3 class="font-semibold mb-4 text-white">Kelola Produk</h3>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('seller.products.edit', $product)); ?>" 
                           class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:border-primary/30 transition-all text-center font-semibold flex items-center justify-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'paint','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'paint','class' => 'w-5 h-5']); ?>
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
                            Edit Produk
                        </a>
                        <button type="button"
                                onclick="
                                    const modal = document.getElementById('delete-product-modal');
                                    if (modal) {
                                        modal.style.display = 'flex';
                                        document.body.style.overflow = 'hidden';
                                    }
                                "
                                class="w-full px-4 py-3 rounded-lg bg-red-500/20 border border-red-500/30 hover:bg-red-500/30 hover:border-red-500/50 transition-all text-red-400 font-semibold flex items-center justify-center gap-2">
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
                            Hapus Produk
                        </button>
                        
                        <?php if (isset($component)) { $__componentOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cfaf2d8c559a20e3495c081df2d0b10 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.confirm-modal','data' => ['id' => 'delete-product-modal','title' => 'Hapus Produk','message' => 'Yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.','confirmText' => 'Ya, Hapus','cancelText' => 'Batal','type' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('confirm-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'delete-product-modal','title' => 'Hapus Produk','message' => 'Yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.','confirm-text' => 'Ya, Hapus','cancel-text' => 'Batal','type' => 'danger']); ?>
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
                        
                        <form id="delete-product-form" method="POST" action="<?php echo e(route('seller.products.destroy', $product)); ?>" style="display: none;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const confirmBtn = document.getElementById('delete-product-modal-confirm-btn');
                                const modal = document.getElementById('delete-product-modal');
                                const form = document.getElementById('delete-product-form');
                                
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
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/products/show.blade.php ENDPATH**/ ?>