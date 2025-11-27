<?php $__env->startSection('title', 'Pesanan Saya - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Pesanan Saya</h1>
        <p class="text-white/60 text-sm sm:text-base">Kelola dan lacak semua pesanan Anda</p>
    </div>
    
    <!-- Prominent Search Bar -->
    <div class="glass p-4 rounded-xl border border-white/10">
        <form method="GET" action="<?php echo e(route('orders.index')); ?>" class="flex gap-3">
            <div class="flex-1 relative">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/40']); ?>
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
                <input type="text" 
                       name="search" 
                       value="<?php echo e(request('search')); ?>" 
                       placeholder="Cari berdasarkan Nama Penjual, No. Pesanan atau Nama Produk" 
                       class="w-full glass border border-white/10 rounded-lg pl-12 pr-4 py-3 bg-white/5 focus:outline-none focus:border-primary focus:bg-white/10 text-base sm:text-sm touch-target">
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all text-sm font-semibold touch-target flex items-center gap-2 min-w-[120px] justify-center">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-5 h-5']); ?>
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
                <span class="hidden sm:inline">Cari</span>
            </button>
            <?php if(request()->filled('search')): ?>
            <a href="<?php echo e(route('orders.index')); ?>" 
               class="px-4 py-3 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center border border-white/10">
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
            </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Tab Navigation -->
    <div class="glass p-2 rounded-xl border border-white/10">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <a href="<?php echo e(route('orders.index')); ?>" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target <?php echo e(!request('status') ? 'bg-primary/20 text-primary border border-primary/30' : 'glass-hover text-white/70 hover:text-white border border-transparent'); ?>">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold <?php echo e(!request('status') ? 'bg-primary/30 text-primary' : 'bg-white/10 text-white/60'); ?>">
                    <?php echo e($orderStatusCounts['all'] ?? 0); ?>

                </span>
            </a>
            
            <a href="<?php echo e(route('orders.index', ['status' => 'pending'])); ?>" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target <?php echo e(request('status') == 'pending' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent'); ?>">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-4 h-4']); ?>
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
                <span>Belum Bayar</span>
                <?php if(($orderStatusCounts['pending'] ?? 0) > 0): ?>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold <?php echo e(request('status') == 'pending' ? 'bg-yellow-500/30 text-yellow-300' : 'bg-white/10 text-white/60'); ?>">
                    <?php echo e($orderStatusCounts['pending']); ?>

                </span>
                <?php endif; ?>
            </a>
            
            <a href="<?php echo e(route('orders.index', ['status' => 'processing'])); ?>" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target <?php echo e(request('status') == 'processing' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent'); ?>">
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
                <span>Sedang Diproses</span>
                <?php if(($orderStatusCounts['processing'] ?? 0) > 0): ?>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold <?php echo e(request('status') == 'processing' ? 'bg-blue-500/30 text-blue-300' : 'bg-white/10 text-white/60'); ?>">
                    <?php echo e($orderStatusCounts['processing']); ?>

                </span>
                <?php endif; ?>
            </a>
            
            <a href="<?php echo e(route('orders.index', ['status' => 'completed'])); ?>" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target <?php echo e(request('status') == 'completed' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent'); ?>">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4']); ?>
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
                <span>Selesai</span>
                <?php if(($orderStatusCounts['completed'] ?? 0) > 0): ?>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold <?php echo e(request('status') == 'completed' ? 'bg-green-500/30 text-green-300' : 'bg-white/10 text-white/60'); ?>">
                    <?php echo e($orderStatusCounts['completed']); ?>

                </span>
                <?php endif; ?>
            </a>
            
            <a href="<?php echo e(route('orders.index', ['status' => 'cancelled'])); ?>" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target <?php echo e(request('status') == 'cancelled' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent'); ?>">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4']); ?>
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
                <span>Dibatalkan</span>
                <?php if(($orderStatusCounts['cancelled'] ?? 0) > 0): ?>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold <?php echo e(request('status') == 'cancelled' ? 'bg-red-500/30 text-red-300' : 'bg-white/10 text-white/60'); ?>">
                    <?php echo e($orderStatusCounts['cancelled']); ?>

                </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
    
    <!-- Advanced Filters (Collapsible) -->
    <div x-data="{ showFilters: false }" class="glass p-4 rounded-xl border border-white/10">
        <button @click="showFilters = !showFilters" 
                class="w-full flex items-center justify-between touch-target">
            <span class="text-sm font-semibold text-white/70">Filter Lanjutan</span>
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-down','class' => 'w-5 h-5 text-white/60 transition-transform','xBind:class' => 'showFilters ? \'rotate-180\' : \'\'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'w-5 h-5 text-white/60 transition-transform','x-bind:class' => 'showFilters ? \'rotate-180\' : \'\'']); ?>
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
        
        <div x-show="showFilters" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mt-4 pt-4 border-t border-white/10">
            <form method="GET" action="<?php echo e(route('orders.index')); ?>" class="space-y-4">
                <input type="hidden" name="status" value="<?php echo e(request('status')); ?>">
                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs text-white/60 mb-2">Tipe</label>
                <select name="type" 
                                class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Tipe</option>
                    <option value="product" <?php echo e(request('type') == 'product' ? 'selected' : ''); ?> class="bg-dark text-white">Produk</option>
                    <option value="service" <?php echo e(request('type') == 'service' ? 'selected' : ''); ?> class="bg-dark text-white">Jasa</option>
                </select>
            </div>

                <div>
                        <label class="block text-xs text-white/60 mb-2">Tanggal Mulai</label>
                    <input type="date" 
                           name="date_from" 
                           value="<?php echo e(request('date_from')); ?>" 
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary text-sm touch-target">
                </div>
                
                <div>
                        <label class="block text-xs text-white/60 mb-2">Tanggal Akhir</label>
                    <input type="date" 
                           name="date_to" 
                           value="<?php echo e(request('date_to')); ?>" 
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary text-sm touch-target">
                </div>

                <div>
                        <label class="block text-xs text-white/60 mb-2">Urutkan</label>
                        <select name="sort" 
                                class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?> class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?> class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                    </select>
                </div>
            </div>

                <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-white/60">Per Halaman:</label>
                        <select name="per_page" 
                                onchange="this.form.submit()" 
                                class="glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="10" <?php echo e(request('per_page', 15) == 10 ? 'selected' : ''); ?> class="bg-dark text-white">10</option>
                        <option value="15" <?php echo e(request('per_page', 15) == 15 ? 'selected' : ''); ?> class="bg-dark text-white">15</option>
                        <option value="20" <?php echo e(request('per_page') == 20 ? 'selected' : ''); ?> class="bg-dark text-white">20</option>
                        <option value="30" <?php echo e(request('per_page') == 30 ? 'selected' : ''); ?> class="bg-dark text-white">30</option>
                    </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-all text-sm font-semibold touch-target">
                            Terapkan
                        </button>
                        <?php if(request()->anyFilled(['type', 'date_from', 'date_to', 'sort', 'per_page'])): ?>
                        <a href="<?php echo e(route('orders.index', ['status' => request('status'), 'search' => request('search')])); ?>" 
                           class="px-5 py-2.5 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target border border-white/10">
                            Reset
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders List -->
    <?php if($orders->count() > 0): ?>
    <div class="space-y-4">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal70cd390af008419cd34cdcb5a55deec7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal70cd390af008419cd34cdcb5a55deec7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.order-card','data' => ['order' => $order]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('order-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['order' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal70cd390af008419cd34cdcb5a55deec7)): ?>
<?php $attributes = $__attributesOriginal70cd390af008419cd34cdcb5a55deec7; ?>
<?php unset($__attributesOriginal70cd390af008419cd34cdcb5a55deec7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal70cd390af008419cd34cdcb5a55deec7)): ?>
<?php $component = $__componentOriginal70cd390af008419cd34cdcb5a55deec7; ?>
<?php unset($__componentOriginal70cd390af008419cd34cdcb5a55deec7); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Pagination -->
    <div class="flex justify-center pt-4">
            <?php echo e($orders->links()); ?>

        </div>
        <?php else: ?>
    <!-- Empty State -->
    <div class="glass p-12 rounded-xl border border-white/10 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'shopping-bag','class' => 'w-10 h-10 text-white/30']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-bag','class' => 'w-10 h-10 text-white/30']); ?>
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
        <h3 class="text-xl font-bold mb-2">Belum ada pesanan</h3>
        <p class="text-white/60 mb-6 text-sm">Mulai berbelanja untuk melihat pesanan Anda di sini</p>
            <a href="<?php echo e(route('products.index')); ?>" 
           class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all font-semibold touch-target">
                Mulai Belanja
            </a>
        </div>
        <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/orders/index.blade.php ENDPATH**/ ?>