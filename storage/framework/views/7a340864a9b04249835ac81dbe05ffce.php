<?php $__env->startSection('title', 'Pengaturan - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8 sm:py-12 max-w-7xl" x-data="{ activeTab: 'platform' }">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-2 flex items-center gap-3">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'settings','class' => 'w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'settings','class' => 'w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-primary']); ?>
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
            Pengaturan Sistem
        </h1>
        <p class="text-white/60 text-sm sm:text-base">Kelola semua pengaturan platform dari sini</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 overflow-x-auto">
        <div class="flex gap-2 sm:gap-3 border-b border-white/10 pb-2 min-w-max">
            <button @click="activeTab = 'platform'" 
                    :class="activeTab === 'platform' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'building','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building','class' => 'w-4 h-4']); ?>
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
                Platform
            </button>
            <button @click="activeTab = 'home'" 
                    :class="activeTab === 'home' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'home','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'home','class' => 'w-4 h-4']); ?>
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
                Home Page
            </button>
            <button @click="activeTab = 'banner'" 
                    :class="activeTab === 'banner' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-4 h-4']); ?>
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
                Banner
            </button>
            <button @click="activeTab = 'contact'" 
                    :class="activeTab === 'contact' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'phone','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'phone','class' => 'w-4 h-4']); ?>
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
                Kontak
            </button>
            <button @click="activeTab = 'bank'" 
                    :class="activeTab === 'bank' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-4 h-4']); ?>
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
                Bank
            </button>
            <button @click="activeTab = 'commission'" 
                    :class="activeTab === 'commission' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'dollar','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'dollar','class' => 'w-4 h-4']); ?>
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
                Komisi
            </button>
            <button @click="activeTab = 'limits'" 
                    :class="activeTab === 'limits' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chart','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chart','class' => 'w-4 h-4']); ?>
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
                Limit
            </button>
            <button @click="activeTab = 'email'" 
                    :class="activeTab === 'email' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mail','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mail','class' => 'w-4 h-4']); ?>
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
                Email
            </button>
            <button @click="activeTab = 'seo'" 
                    :class="activeTab === 'seo' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-4 h-4']); ?>
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
                SEO
            </button>
            <button @click="activeTab = 'features'" 
                    :class="activeTab === 'features' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'lightning','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lightning','class' => 'w-4 h-4']); ?>
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
                Fitur
            </button>
            <button @click="activeTab = 'hours'" 
                    :class="activeTab === 'hours' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
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
                Jam Operasional
            </button>
            <button @click="activeTab = 'owner'" 
                    :class="activeTab === 'owner' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'user','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user','class' => 'w-4 h-4']); ?>
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
                Owner & Founder
            </button>
            <button @click="activeTab = 'featured'" 
                    :class="activeTab === 'featured' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'award','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'award','class' => 'w-4 h-4']); ?>
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
                Featured Promosi
            </button>
            <button @click="activeTab = 'api'" 
                    :class="activeTab === 'api' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-4 h-4']); ?>
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
                API Settings
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="space-y-6">
        <!-- Platform Settings Tab -->
        <div x-show="activeTab === 'platform'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'building','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'building','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Platform
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.platform')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Platform</label>
                        <input type="text" name="site_name" value="<?php echo e($platformSettings['site_name'] ?? 'Ebrystoree'); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Nama ini akan muncul di navbar dan title halaman</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Tagline</label>
                        <input type="text" name="tagline" value="<?php echo e($platformSettings['tagline'] ?? ''); ?>"
                               placeholder="Marketplace terpercaya untuk produk digital dan jasa joki tugas"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <!-- Logo & Favicon Upload -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                         x-data="{
                             logoPreview: null,
                             faviconPreview: null,
                             logoUrl: '<?php echo e($platformSettings['logo'] ?? ''); ?>',
                             faviconUrl: '<?php echo e($platformSettings['favicon'] ?? ''); ?>',
                             showLogoUrlInput: false,
                             showFaviconUrlInput: false,
                             init() {
                                 <?php if(!empty($platformSettings['logo_url'])): ?>
                                 this.logoPreview = '<?php echo e($platformSettings['logo_url']); ?>';
                                 this.logoUrl = '<?php echo e($platformSettings['logo'] ?? ''); ?>';
                                 <?php endif; ?>
                                 <?php if(!empty($platformSettings['favicon_url'])): ?>
                                 this.faviconPreview = '<?php echo e($platformSettings['favicon_url']); ?>';
                                 <?php endif; ?>
                             }
                         }">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Logo</label>
                            <div class="space-y-3">
                                <!-- File Upload -->
                                <div class="relative">
                                    <input type="file" 
                                           name="logo_file" 
                                           id="logo_file"
                                           accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   logoPreview = URL.createObjectURL(file);
                                                   showLogoUrlInput = false;
                                                   document.getElementById('logo_url_input').value = '';
                                               }
                                           "
                                           class="hidden">
                                    <label for="logo_file" 
                                           class="flex items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                        <div class="text-center px-4">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']); ?>
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
                                            <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                                <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-white/50 mt-1">PNG, JPG, SVG, WebP (Max 2MB)</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Preview & Current Logo -->
                                <div class="glass p-4 rounded-lg border border-white/10">
                                    <p class="text-xs text-white/60 mb-3 font-medium">Preview Logo:</p>
                                    <div class="space-y-3">
                                        <!-- Preview Box (Larger) -->
                                        <div class="w-full min-h-[120px] sm:min-h-[150px] rounded-lg bg-white/5 border border-white/10 overflow-hidden flex items-center justify-center p-4">
                                            <template x-if="logoPreview">
                                                <img :src="logoPreview" alt="Logo Preview" class="max-w-full max-h-[120px] sm:max-h-[150px] object-contain">
                                            </template>
                                            <template x-if="!logoPreview">
                                                <div class="text-center">
                                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-12 h-12 text-white/40 mx-auto mb-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-12 h-12 text-white/40 mx-auto mb-2']); ?>
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
                                                    <p class="text-xs text-white/50">Belum ada logo</p>
                                                </div>
                                            </template>
                                        </div>
                                        <!-- Info & Actions -->
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs text-white/60" x-text="logoPreview ? 'Logo siap digunakan' : 'Upload logo untuk melihat preview'"></p>
                                            <button type="button" 
                                                    @click="logoPreview = null; document.getElementById('logo_file').value = '';"
                                                    x-show="logoPreview"
                                                    class="text-xs text-red-400 hover:text-red-300 transition-colors px-2 py-1 rounded hover:bg-red-500/10">
                                                Hapus Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- URL Input (Alternative) -->
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="showLogoUrlInput = !showLogoUrlInput"
                                            class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-3 h-3']); ?>
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
                                        <span x-text="showLogoUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                    </button>
                                </div>
                                <div x-show="showLogoUrlInput" x-cloak class="space-y-2">
                                    <input type="text" 
                                           name="logo" 
                                           id="logo_url_input"
                                           :value="logoUrl"
                                   placeholder="/images/logo.png atau URL lengkap"
                                           @input="logoUrl = $event.target.value; if (logoUrl) logoPreview = logoUrl;"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                        </div>
                            </div>
                        </div>
                        
                        <!-- Favicon Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Favicon</label>
                            <div class="space-y-3">
                                <!-- File Upload -->
                                <div class="relative">
                                    <input type="file" 
                                           name="favicon_file" 
                                           id="favicon_file"
                                           accept="image/png,image/x-icon,image/svg+xml,image/jpeg,image/jpg"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   faviconPreview = URL.createObjectURL(file);
                                                   showFaviconUrlInput = false;
                                                   document.getElementById('favicon_url_input').value = '';
                                               }
                                           "
                                           class="hidden">
                                    <label for="favicon_file" 
                                           class="flex items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                        <div class="text-center px-4">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']); ?>
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
                                            <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                                <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-white/50 mt-1">ICO, PNG, SVG (Max 1MB)</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Preview & Current Favicon -->
                                <div class="glass p-3 rounded-lg border border-white/10">
                                    <p class="text-xs text-white/60 mb-2">Preview:</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-16 rounded-lg bg-white/5 border border-white/10 overflow-hidden flex items-center justify-center flex-shrink-0">
                                            <template x-if="faviconPreview">
                                                <img :src="faviconPreview" alt="Favicon Preview" class="w-full h-full object-contain">
                                            </template>
                                            <template x-if="!faviconPreview">
                                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-6 h-6 text-white/40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-6 h-6 text-white/40']); ?>
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
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-white/60 mb-1" x-text="faviconPreview ? 'Favicon dipilih' : 'Belum ada favicon'"></p>
                                            <button type="button" 
                                                    @click="faviconPreview = null; document.getElementById('favicon_file').value = '';"
                                                    x-show="faviconPreview"
                                                    class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- URL Input (Alternative) -->
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="showFaviconUrlInput = !showFaviconUrlInput"
                                            class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-3 h-3']); ?>
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
                                        <span x-text="showFaviconUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                    </button>
                                </div>
                                <div x-show="showFaviconUrlInput" x-cloak class="space-y-2">
                                    <input type="text" 
                                           name="favicon" 
                                           id="favicon_url_input"
                                           :value="faviconUrl"
                                   placeholder="/images/favicon.ico atau URL lengkap"
                                           @input="faviconUrl = $event.target.value; if (faviconUrl) faviconPreview = faviconUrl;"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Currency</label>
                            <input type="text" name="currency" value="<?php echo e($platformSettings['currency'] ?? 'IDR'); ?>"
                                   placeholder="IDR, USD, etc"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Timezone</label>
                            <input type="text" name="timezone" value="<?php echo e($platformSettings['timezone'] ?? 'Asia/Jakarta'); ?>"
                                   placeholder="Asia/Jakarta"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bell','class' => 'w-4 h-4 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-4 h-4 text-primary']); ?>
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
                            Informasi Sistem (System Announcement)
                        </label>
                        <input type="text" 
                               name="system_announcement" 
                               value="<?php echo e($platformSettings['system_announcement'] ?? ''); ?>"
                               placeholder="Contoh: Sabarr yah lagi ada pembaruan"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1.5 flex items-center gap-1.5">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'w-3 h-3']); ?>
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
                            Informasi ini akan ditampilkan sebagai banner bergerak di bagian atas halaman
                        </p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Pengaturan Platform
                </button>
            </form>
        </div>

        <!-- Home Settings Tab -->
        <div x-show="activeTab === 'home'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'home','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'home','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Halaman Home
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.home')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Title</label>
                        <input type="text" name="hero_title" value="<?php echo e($homeSettings['hero_title'] ?? 'Selamat Datang di'); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Judul utama di hero section</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" value="<?php echo e($homeSettings['hero_subtitle'] ?? 'Ebrystoree'); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Subtitle yang akan ditampilkan dengan highlight</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Description</label>
                        <textarea name="hero_description" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($homeSettings['hero_description'] ?? 'Marketplace terpercaya untuk produk digital dan jasa joki tugas berkualitas tinggi'); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Badge</label>
                        <input type="text" name="hero_badge" value="<?php echo e($homeSettings['hero_badge'] ?? '✨ Platform Terpercaya #1 di Indonesia'); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Background Color</label>
                            <input type="text" name="home_background_color" value="<?php echo e($homeSettings['home_background_color'] ?? ''); ?>"
                                   placeholder="#1a1a1a atau rgba(26, 26, 26, 1)"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Background Image URL</label>
                            <input type="text" name="home_background_image" value="<?php echo e($homeSettings['home_background_image'] ?? ''); ?>"
                                   placeholder="/images/background.jpg atau URL lengkap"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Pengaturan Home
                </button>
            </form>
        </div>

        <!-- Banner Settings Tab -->
        <div x-show="activeTab === 'banner'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10" 
             x-data="{ 
                 bannerImagePreview: null,
                 showBannerUrlInput: false,
                 bannerImageUrl: '<?php echo e($bannerSettings['banner_image'] ?? ''); ?>',
                 bannerEnabled: <?php echo e($bannerSettings['banner_enabled'] ?? true ? 'true' : 'false'); ?>,
                 init() {
                     <?php if(!empty($bannerSettings['banner_image_url'])): ?>
                     this.bannerImagePreview = '<?php echo e($bannerSettings['banner_image_url']); ?>';
                     <?php endif; ?>
                 }
             }">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Banner Selamat Datang
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.banner')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="space-y-6">
                    <!-- Enable/Disable Banner -->
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Aktifkan Banner</label>
                            <p class="text-xs text-white/60">Tampilkan banner di halaman beranda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="banner_enabled" value="1" 
                                   x-model="bannerEnabled"
                                   <?php echo e(($bannerSettings['banner_enabled'] ?? true) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Banner Image Upload -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Gambar Banner</label>
                        <div class="space-y-3">
                            <!-- File Upload -->
                            <div class="relative">
                                <input type="file" 
                                       name="banner_image_file" 
                                       id="banner_image_file"
                                       accept="image/jpeg,image/jpg,image/png,image/webp"
                                       @change="
                                           const file = $event.target.files[0];
                                           if (file) {
                                               bannerImagePreview = URL.createObjectURL(file);
                                               showBannerUrlInput = false;
                                               document.getElementById('banner_url_input').value = '';
                                           }
                                       "
                                       class="hidden">
                                <label for="banner_image_file" 
                                       class="flex items-center justify-center w-full h-40 sm:h-48 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                    <div class="text-center px-4">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-10 h-10 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-10 h-10 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors']); ?>
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
                                        <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                            <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <p class="text-xs text-white/50 mt-1">JPEG, PNG, WebP (Max 5MB)</p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Preview & Current Banner -->
                            <div class="glass p-4 rounded-lg border border-white/10">
                                <p class="text-xs text-white/60 mb-3">Preview:</p>
                                <div class="relative w-full h-48 sm:h-64 rounded-lg overflow-hidden bg-white/5 border border-white/10">
                                    <template x-if="bannerImagePreview">
                                        <img :src="bannerImagePreview" alt="Banner Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!bannerImagePreview">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'image','class' => 'w-16 h-16 text-white/40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'image','class' => 'w-16 h-16 text-white/40']); ?>
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
                                    </template>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <p class="text-xs text-white/60" x-text="bannerImagePreview ? 'Banner dipilih' : 'Belum ada banner'"></p>
                                    <button type="button" 
                                            @click="bannerImagePreview = null; document.getElementById('banner_image_file').value = '';"
                                            x-show="bannerImagePreview"
                                            class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                        Hapus Preview
                                    </button>
                                </div>
                            </div>
                            
                            <!-- URL Input (Alternative) -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="showBannerUrlInput = !showBannerUrlInput"
                                        class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-3 h-3']); ?>
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
                                    <span x-text="showBannerUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                </button>
                            </div>
                            <div x-show="showBannerUrlInput" x-cloak class="space-y-2">
                                <input type="text" 
                                       name="banner_image" 
                                       id="banner_url_input"
                                       :value="bannerImageUrl"
                                       placeholder="https://example.com/banner.jpg atau URL lengkap"
                                       @input="bannerImageUrl = $event.target.value; if (bannerImageUrl) bannerImagePreview = bannerImageUrl;"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Banner Content -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Judul Banner</label>
                            <input type="text" name="banner_title" value="<?php echo e($bannerSettings['banner_title'] ?? 'Selamat Datang di Ebrystoree'); ?>"
                                   placeholder="Selamat Datang di Ebrystoree"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Teks Tombol</label>
                            <input type="text" name="banner_button_text" value="<?php echo e($bannerSettings['banner_button_text'] ?? 'Mulai Belanja'); ?>"
                                   placeholder="Mulai Belanja"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Subtitle/Deskripsi</label>
                        <textarea name="banner_subtitle" rows="2"
                                  placeholder="Marketplace terpercaya untuk produk digital dan jasa joki tugas"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($bannerSettings['banner_subtitle'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Link Tombol</label>
                        <input type="text" name="banner_button_link" value="<?php echo e($bannerSettings['banner_button_link'] ?? route('products.index')); ?>"
                               placeholder="<?php echo e(route('products.index')); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">URL tujuan saat tombol diklik</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Opacity Overlay</label>
                        <input type="number" name="banner_overlay_opacity" 
                               value="<?php echo e($bannerSettings['banner_overlay_opacity'] ?? 0.4); ?>"
                               min="0" max="1" step="0.1"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Tingkat kegelapan overlay (0 = transparan, 1 = gelap penuh)</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Pengaturan Banner
                </button>
            </form>
        </div>

        <!-- Contact Info Tab -->
        <div x-show="activeTab === 'contact'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'phone','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'phone','class' => 'w-6 h-6 text-primary']); ?>
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
                Informasi Kontak
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.contact')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="<?php echo e($contactInfo['email'] ?? ''); ?>"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Telepon</label>
                            <input type="text" name="phone" value="<?php echo e($contactInfo['phone'] ?? ''); ?>"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" value="<?php echo e($contactInfo['whatsapp'] ?? ''); ?>"
                               placeholder="6281234567890 (tanpa +)"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Alamat</label>
                        <textarea name="address" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($contactInfo['address'] ?? ''); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Kontak
                </button>
            </form>
        </div>

        <!-- Bank Account Tab -->
        <div x-show="activeTab === 'bank'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-6 h-6 text-primary']); ?>
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
                Informasi Rekening Bank
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.bankAccount')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" value="<?php echo e($bankAccountInfo['bank_name'] ?? ''); ?>"
                               placeholder="Bank BCA, Bank Mandiri, dll"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nomor Rekening</label>
                            <input type="text" name="bank_account_number" value="<?php echo e($bankAccountInfo['bank_account_number'] ?? ''); ?>"
                                   placeholder="1234567890"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Atas Nama</label>
                            <input type="text" name="bank_account_name" value="<?php echo e($bankAccountInfo['bank_account_name'] ?? ''); ?>"
                                   placeholder="PT Ebrystoree"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">QRIS Code (URL atau Base64)</label>
                        <textarea name="qris_code" rows="3" placeholder="URL QRIS atau Base64 string"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($bankAccountInfo['qris_code'] ?? ''); ?></textarea>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Rekening Bank
                </button>
            </form>
        </div>

        <!-- Commission Settings Tab -->
        <div x-show="activeTab === 'commission'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'dollar','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'dollar','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Komisi
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.commission')); ?>">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="glass p-6 rounded-lg border border-white/10">
                        <label class="block text-sm font-medium mb-2">Komisi Produk (%)</label>
                        <input type="number" name="commission_product" value="<?php echo e($commissionSettings['commission_product'] ?? 10); ?>"
                               min="0" max="100" step="0.1" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-2">Persentase komisi untuk setiap penjualan produk digital</p>
                    </div>
                    <div class="glass p-6 rounded-lg border border-white/10">
                        <label class="block text-sm font-medium mb-2">Komisi Jasa (%)</label>
                        <input type="number" name="commission_service" value="<?php echo e($commissionSettings['commission_service'] ?? 15); ?>"
                               min="0" max="100" step="0.1" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-2">Persentase komisi untuk setiap penjualan jasa joki tugas</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Komisi
                </button>
            </form>
        </div>

        <!-- Limits Tab -->
        <div x-show="activeTab === 'limits'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chart','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chart','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Limit
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.limits')); ?>">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Top-up (Rp)</label>
                        <input type="number" name="min_topup_amount" value="<?php echo e($limits['min_topup_amount'] ?? 10000); ?>"
                               min="1000" step="1000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Max Top-up (Rp)</label>
                        <input type="number" name="max_topup_amount" value="<?php echo e($limits['max_topup_amount'] ?? 10000000); ?>"
                               min="1000" step="100000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Order (Rp)</label>
                        <input type="number" name="min_order_amount" value="<?php echo e($limits['min_order_amount'] ?? 1000); ?>"
                               min="0" step="100" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Withdrawal (Rp)</label>
                        <input type="number" name="min_withdrawal_amount" value="<?php echo e($limits['min_withdrawal_amount'] ?? 50000); ?>"
                               min="0" step="10000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Max Withdrawal (Rp)</label>
                        <input type="number" name="max_withdrawal_amount" value="<?php echo e($limits['max_withdrawal_amount'] ?? 50000000); ?>"
                               min="0" step="1000000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Limit
                </button>
            </form>
        </div>

        <!-- Email Settings Tab -->
        <div x-show="activeTab === 'email'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mail','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mail','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Email
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.email')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Admin Email</label>
                        <input type="email" name="admin_email" value="<?php echo e($emailSettings['admin_email'] ?? ''); ?>"
                               placeholder="admin@ebrystoree.com"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">From Name</label>
                            <input type="text" name="email_from_name" value="<?php echo e($emailSettings['email_from_name'] ?? 'Ebrystoree'); ?>"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">From Address</label>
                            <input type="email" name="email_from_address" value="<?php echo e($emailSettings['email_from_address'] ?? 'noreply@ebrystoree.com'); ?>"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Email
                </button>
            </form>
        </div>

        <!-- SEO Settings Tab -->
        <div x-show="activeTab === 'seo'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'search','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan SEO
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.seo')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="<?php echo e($seoSettings['meta_title'] ?? ''); ?>"
                               placeholder="Ebrystoree - Marketplace Produk Digital & Jasa Joki Tugas"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                                  placeholder="Deskripsi untuk SEO (150-160 karakter)"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($seoSettings['meta_description'] ?? ''); ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="<?php echo e($seoSettings['meta_keywords'] ?? ''); ?>"
                               placeholder="keyword1, keyword2, keyword3"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan SEO
                </button>
            </form>
        </div>

        <!-- Feature Flags Tab -->
        <div x-show="activeTab === 'features'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'lightning','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lightning','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Fitur
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.features')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Wallet</label>
                            <p class="text-xs text-white/60">Aktifkan sistem wallet untuk top-up dan pembayaran</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_wallet" value="1" 
                                   <?php echo e(($featureFlags['enable_wallet'] ?? true) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Bank Transfer</label>
                            <p class="text-xs text-white/60">Aktifkan metode pembayaran transfer bank</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_bank_transfer" value="1" 
                                   <?php echo e(($featureFlags['enable_bank_transfer'] ?? true) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable QRIS</label>
                            <p class="text-xs text-white/60">Aktifkan metode pembayaran QRIS</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_qris" value="1" 
                                   <?php echo e(($featureFlags['enable_qris'] ?? true) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Seller Registration</label>
                            <p class="text-xs text-white/60">Aktifkan pendaftaran seller baru</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_seller_registration" value="1" 
                                   <?php echo e(($featureFlags['enable_seller_registration'] ?? true) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border-2 border-yellow-500/30 bg-yellow-500/5">
                        <div>
                            <label class="font-semibold text-yellow-400">Maintenance Mode</label>
                            <p class="text-xs text-white/60">Mode maintenance akan menampilkan banner dan disable checkout</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                   <?php echo e(($featureFlags['maintenance_mode'] ?? false) ? 'checked' : ''); ?>

                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        </label>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Fitur
                </button>
            </form>
        </div>

        <!-- Business Hours Tab -->
        <div x-show="activeTab === 'hours'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'clock','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'clock','class' => 'w-6 h-6 text-primary']); ?>
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
                Jam Operasional
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.businessHours')); ?>">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Jam Buka</label>
                        <input type="time" name="open" value="<?php echo e($businessHours['open'] ?? '09:00'); ?>" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jam Tutup</label>
                        <input type="time" name="close" value="<?php echo e($businessHours['close'] ?? '21:00'); ?>" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Jam Operasional
                </button>
            </form>
        </div>

        <!-- Owner Settings Tab -->
        <div x-show="activeTab === 'owner'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'user','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user','class' => 'w-6 h-6 text-primary']); ?>
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
                Owner & Founder Settings
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.owner')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Owner</label>
                        <input type="text" name="owner_name" value="<?php echo e($ownerSettings['owner_name'] ?? 'Febryanus Tambing'); ?>"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Nama lengkap owner/founder</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Jabatan/Title</label>
                        <input type="text" name="owner_title" value="<?php echo e($ownerSettings['owner_title'] ?? 'Owner & Founder'); ?>"
                               placeholder="Owner & Founder"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Jabatan yang akan ditampilkan (contoh: Owner & Founder)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="owner_description" rows="4"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"><?php echo e($ownerSettings['owner_description'] ?? ''); ?></textarea>
                        <p class="text-xs text-white/60 mt-1">Deskripsi tentang owner (akan ditampilkan di halaman About)</p>
                    </div>

                    <div x-data="{ 
                        photoPreview: '',
                        showPreview: false,
                        currentPhoto: <?php echo \Illuminate\Support\Js::from($ownerSettings['owner_photo'] ?? '')->toHtml() ?>,
                        hasCurrentPhoto: <?php echo \Illuminate\Support\Js::from(!empty($ownerSettings['owner_photo']))->toHtml() ?>,
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                if (!file.type.startsWith('image/')) {
                                    window.dispatchEvent(new CustomEvent('toast', { 
                                        detail: { 
                                            message: 'File harus berupa gambar!', 
                                            type: 'error' 
                                        } 
                                    }));
                                    event.target.value = '';
                                    return;
                                }
                                if (file.size > 5 * 1024 * 1024) {
                                    window.dispatchEvent(new CustomEvent('toast', { 
                                        detail: { 
                                            message: 'Ukuran file maksimal 5MB!', 
                                            type: 'error' 
                                        } 
                                    }));
                                    event.target.value = '';
                                    return;
                                }
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.photoPreview = e.target.result;
                                    this.showPreview = true;
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        removePreview() {
                            this.photoPreview = '';
                            this.showPreview = false;
                            document.getElementById('owner_photo_file').value = '';
                        }
                    }">
                        <label class="block text-sm font-medium mb-2">Foto Owner</label>
                        
                        <!-- File Input -->
                        <div class="mb-3">
                            <label for="owner_photo_file" class="cursor-pointer">
                                <div class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 hover:bg-white/10 hover:border-primary/40 transition-all duration-300 flex items-center justify-center gap-3">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'camera','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'camera','class' => 'w-5 h-5 text-primary']); ?>
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
                                    <span class="text-sm font-medium text-white/90">
                                        <span x-show="!showPreview">Pilih Foto</span>
                                        <span x-show="showPreview">Ganti Foto</span>
                                    </span>
                                </div>
                            </label>
                            <input type="file" 
                                   id="owner_photo_file" 
                                   name="owner_photo_file" 
                                   accept="image/*"
                                   @change="handleFileSelect($event)"
                                   class="hidden">
                            <p class="text-xs text-white/60 mt-2">Format: JPG, PNG, atau GIF. Maksimal 5MB</p>
                        </div>

                        <!-- Preview (New File Selected) -->
                        <div x-show="showPreview" class="mt-4">
                            <p class="text-xs text-white/60 mb-2">Preview foto baru:</p>
                            <div class="relative inline-block">
                                <img :src="photoPreview" 
                                     alt="Owner Photo Preview" 
                                     class="w-40 h-40 rounded-full object-cover border-4 border-primary/30 shadow-lg">
                                <button type="button" 
                                        @click="removePreview()"
                                        class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors shadow-lg">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-4 h-4 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-4 h-4 text-white']); ?>
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

                        <!-- Current Photo (if exists and no new file selected) -->
                        <div x-show="!showPreview && hasCurrentPhoto" class="mt-4">
                            <p class="text-xs text-white/60 mb-2">Foto saat ini:</p>
                            <div class="relative inline-block">
                                <img :src="currentPhoto" 
                                     alt="Current Owner Photo" 
                                     class="w-40 h-40 rounded-full object-cover border-4 border-primary/30 shadow-lg">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Badges (JSON Array)</label>
                        <textarea name="owner_badges" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm"><?php echo e(json_encode($ownerSettings['owner_badges'] ?? ['Visionary Leader', 'Innovation Driven', 'Customer Focused'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></textarea>
                        <p class="text-xs text-white/60 mt-1">Array JSON untuk badges (contoh: ["Visionary Leader","Innovation Driven","Customer Focused"])</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                    Simpan Owner Settings
                </button>
            </form>
        </div>

        <!-- Featured Promosi Tab -->
        <div x-show="activeTab === 'featured'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'award','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'award','class' => 'w-6 h-6 text-primary']); ?>
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
                Pengaturan Featured Promosi
            </h2>
            <p class="text-white/60 mb-6 text-sm">Kelola produk/jasa yang ditampilkan sebagai featured di halaman home untuk promosi berbayar.</p>
            
            <?php
                $featuredItems = \App\Models\FeaturedItem::with(['product', 'service'])->ordered()->get();
                $products = \App\Models\Product::where('is_active', true)->where('is_draft', false)->orderBy('title')->get();
                $services = \App\Models\Service::where('status', 'active')->orderBy('title')->get();
            ?>

            <!-- List Featured Items -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Featured Items Aktif</h3>
                <?php if($featuredItems->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $featuredItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="glass p-4 rounded-lg border border-white/10">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 bg-primary/20 text-primary rounded text-xs font-semibold"><?php echo e(strtoupper($item->type)); ?></span>
                                    <span class="text-white/90 font-semibold"><?php echo e($item->display_title); ?></span>
                                    <?php if($item->is_active): ?>
                                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Aktif</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-xs">Nonaktif</span>
                                    <?php endif; ?>
    </div>
                                <p class="text-sm text-white/60"><?php echo e($item->title ?? 'Tidak ada custom title'); ?></p>
</div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="<?php echo e(route('admin.settings.featured.delete', $item->id)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            onclick="return confirm('Hapus featured item ini?')"
                                            class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-sm transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <p class="text-white/60 text-sm">Belum ada featured items.</p>
                <?php endif; ?>
            </div>

            <!-- Add New Featured Item -->
            <div class="border-t border-white/10 pt-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Featured Item Baru</h3>
                <form method="POST" action="<?php echo e(route('admin.settings.featured')); ?>" x-data="{ type: 'product', itemId: '' }">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Tipe</label>
                                <select name="type" 
                                        x-model="type"
                                            @change="itemId = ''"
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="product">Produk</option>
                                    <option value="service">Jasa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Pilih Item</label>
                                <!-- Products Dropdown -->
                                <select name="item_id" 
                                        x-model="itemId"
                                        x-show="type === 'product'"
                                        required
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="">-- Pilih Produk --</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($product->id); ?>"><?php echo e($product->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <!-- Services Dropdown -->
                                <select name="item_id" 
                                        x-model="itemId"
                                        x-show="type === 'service'"
                                        required
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="">-- Pilih Jasa --</option>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Custom Title/Banner Text (Opsional)</label>
                            <input type="text" 
                                   name="title" 
                                   placeholder="Contoh: HQ Aged Domain (Premium Backlinks)"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                            <p class="text-xs text-white/60 mt-1">Jika kosong, akan menggunakan title dari produk/jasa</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description (Opsional)</label>
                            <textarea name="description" 
                                      rows="2"
                                      placeholder="Deskripsi singkat untuk featured item"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Header BG Color</label>
                                <input type="color" 
                                       name="header_bg_color" 
                                       value="#8B4513"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Banner BG Color</label>
                                <input type="color" 
                                       name="banner_bg_color" 
                                       value="#DC2626"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Main BG Color</label>
                                <input type="color" 
                                       name="main_bg_color" 
                                       value="#000000"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Main Text Color</label>
                                <input type="color" 
                                       name="main_text_color" 
                                       value="#FFFFFF"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Accent Color</label>
                                <input type="color" 
                                       name="accent_color" 
                                       value="#FCD34D"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Features (JSON Array - Opsional)</label>
                            <textarea name="features" 
                                      rows="3"
                                      placeholder='["High DR/DA/PA | Low Spam Score", "Age 10+ Years | Bebas Nawala", "HQ Backlink"]'
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm"></textarea>
                            <p class="text-xs text-white/60 mt-1">Array JSON untuk features list (contoh: ["Feature 1", "Feature 2"])</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Footer Text (Opsional)</label>
                            <input type="text" 
                                   name="footer_text" 
                                   placeholder="Contoh: DR+ DA+ PA+"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Sort Order</label>
                                <input type="number" 
                                       name="sort_order" 
                                       value="0"
                                       min="0"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           checked
                                           class="rounded border-white/20">
                                    <span>Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                        Tambah Featured Item
                    </button>
                </form>
            </div>
        </div>

        <!-- API Settings Tab -->
        <div x-show="activeTab === 'api'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'link','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'link','class' => 'w-6 h-6 text-primary']); ?>
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
                API Settings - Khfy Store
            </h2>
            <form method="POST" action="<?php echo e(route('admin.settings.api')); ?>">
                <?php echo csrf_field(); ?>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'lock','class' => 'w-4 h-4 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'lock','class' => 'w-4 h-4 text-primary']); ?>
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
                            <span>API Key Khfy Store *</span>
                        </label>
                        <input type="text" 
                               name="khfy_api_key" 
                               value="<?php echo e(session('updated_api_key') ?? $settings['khfy_api_key'] ?? ''); ?>"
                               placeholder="Masukkan API key dari panel.khfy-store.com"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm"
                               required>
                        <p class="text-xs text-white/60 mt-2">
                            API key ini digunakan untuk integrasi dengan Khfy Store (pembelian kuota XL).
                            Dapatkan API key di <strong>Profile → Pengaturan</strong> di panel.khfy-store.com
                        </p>
                    </div>

                    <div class="glass p-4 rounded-lg border border-yellow-500/30 bg-yellow-500/10">
                        <div class="flex items-start gap-3">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5']); ?>
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
                            <div class="flex-1">
                                <h3 class="font-semibold text-yellow-400 mb-2">Webhook URL</h3>
                                <p class="text-sm text-white/80 mb-2">
                                    Salin URL berikut dan pasang di <strong>Profile → Pengaturan → Webhook</strong> di panel.khfy-store.com:
                                </p>
                                <div class="flex items-center gap-2">
                                    <input type="text" 
                                           readonly
                                           value="<?php echo e(route('quota.webhook')); ?>"
                                           class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 font-mono text-xs sm:text-sm"
                                           id="webhook-url">
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('<?php echo e(route('quota.webhook')); ?>').then(() => window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Webhook URL berhasil disalin!', type: 'success'}})))"
                                            class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold flex items-center gap-2 touch-target">
                                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'copy','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'copy','class' => 'w-4 h-4']); ?>
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
                                        <span>Salin</span>
                                    </button>
                                </div>
                                <p class="text-xs text-white/60 mt-2">
                                    Webhook ini akan menerima update status transaksi secara real-time dari Khfy Store.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <button type="button" 
                                onclick="syncProducts()"
                                class="px-6 py-3 glass glass-hover rounded-lg transition-colors font-semibold flex items-center gap-2 text-white"
                                id="sync-products-btn">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'refresh','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'refresh','class' => 'w-5 h-5']); ?>
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
                            <span id="sync-products-text">Add Produk Otomatis</span>
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-5 h-5']); ?>
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
                            Simpan API Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function syncProducts() {
    const btn = document.getElementById('sync-products-btn');
    const text = document.getElementById('sync-products-text');
    const originalText = text.textContent;
    
    // Disable button dan show loading
    btn.disabled = true;
    text.textContent = 'Memproses...';
    
    fetch('<?php echo e(route('admin.settings.syncProducts')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: data.message || `Berhasil menambahkan ${data.count || 0} produk`,
                    type: 'success'
                }
            }));
            
            // Optional: Redirect to quota page after successful sync
            if (data.redirect && data.count > 0) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        } else {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: data.message || 'Gagal menambahkan produk',
                    type: 'error'
                }
            }));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                message: 'Terjadi kesalahan saat menambahkan produk',
                type: 'error'
            }
        }));
    })
    .finally(() => {
        btn.disabled = false;
        text.textContent = originalText;
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/settings/index.blade.php ENDPATH**/ ?>