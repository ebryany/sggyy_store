<?php $__env->startSection('title', 'Produk Digital - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Produk Digital</h1>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->isSeller() || auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('seller.products.create')); ?>" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm sm:text-base text-center touch-target">
                + Tambah Produk
            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <!-- Search & Filter -->
    <div class="glass p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
            <form method="GET" action="<?php echo e(route('products.index')); ?>" class="space-y-4">
            <!-- Row 1: Search & Category -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <input type="text" 
                       name="search" 
                       value="<?php echo e(request('search')); ?>" 
                       placeholder="Cari produk..." 
                       class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                
                <select name="category" class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Kategori</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category); ?>" <?php echo e(request('category') === $category ? 'selected' : ''); ?> class="bg-dark text-white">
                        <?php echo e($category); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Row 2: Price Range & Rating -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs text-white/60 mb-1">Harga Min</label>
                    <input type="number" 
                           name="min_price" 
                           value="<?php echo e(request('min_price')); ?>" 
                           placeholder="0" 
                           min="0"
                           step="1000"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>
                
                <div>
                    <label class="block text-xs text-white/60 mb-1">Harga Max</label>
                    <input type="number" 
                           name="max_price" 
                           value="<?php echo e(request('max_price')); ?>" 
                           placeholder="Tidak terbatas" 
                           min="0"
                           step="1000"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Rating Min</label>
                    <select name="rating" class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="" class="bg-dark text-white">Semua Rating</option>
                        <option value="5" <?php echo e(request('rating') == '5' ? 'selected' : ''); ?> class="bg-dark text-white">⭐ 5</option>
                        <option value="4" <?php echo e(request('rating') == '4' ? 'selected' : ''); ?> class="bg-dark text-white">⭐ 4+</option>
                        <option value="3" <?php echo e(request('rating') == '3' ? 'selected' : ''); ?> class="bg-dark text-white">⭐ 3+</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Urutkan</label>
                    <select name="sort" class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?> class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" <?php echo e(request('sort') == 'oldest' ? 'selected' : ''); ?> class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?> class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                        <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?> class="bg-dark text-white">Rating Tertinggi</option>
                        <option value="popular" <?php echo e(request('sort') == 'popular' ? 'selected' : ''); ?> class="bg-dark text-white">Paling Populer</option>
                        <option value="sold" <?php echo e(request('sort') == 'sold' ? 'selected' : ''); ?> class="bg-dark text-white">Terlaris</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Actions & Per Page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="px-4 sm:px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-base sm:text-sm touch-target">
                        Terapkan Filter
                    </button>
                    
                    <?php if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'rating', 'sort', 'seller', 'in_stock', 'per_page'])): ?>
                    <a href="<?php echo e(route('products.index')); ?>" class="px-4 sm:px-6 py-2 glass glass-hover rounded-lg text-center text-base sm:text-sm touch-target">
                        Reset
                    </a>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-white/60">Per Halaman:</label>
                    <select name="per_page" onchange="this.form.submit()" class="glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="12" <?php echo e(request('per_page', 12) == 12 ? 'selected' : ''); ?> class="bg-dark text-white">12</option>
                        <option value="24" <?php echo e(request('per_page') == 24 ? 'selected' : ''); ?> class="bg-dark text-white">24</option>
                        <option value="48" <?php echo e(request('per_page') == 48 ? 'selected' : ''); ?> class="bg-dark text-white">48</option>
                        <option value="96" <?php echo e(request('per_page') == 96 ? 'selected' : ''); ?> class="bg-dark text-white">96</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('components.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-12">
                <p class="text-white/60 text-lg mb-4">Belum ada produk tersedia.</p>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isSeller() || auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('seller.products.create')); ?>" 
                       class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                        Tambah Produk Pertama
                    </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if($products->hasPages()): ?>
    <div class="mt-8">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>






<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/products/index.blade.php ENDPATH**/ ?>