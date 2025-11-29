<div x-data="toast()" 
     x-show="visible" 
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="fixed top-4 right-3 sm:right-4 z-50 max-w-md w-[calc(100%-1.5rem)] sm:w-full pl-safe pr-safe"
     id="toast-container"
     style="display: none;">
    <div class="glass border rounded-lg p-3 sm:p-4 shadow-2xl backdrop-blur-xl"
         :class="{
             'bg-red-500/20 border-red-500/50': type === 'error',
             'bg-green-500/20 border-green-500/50': type === 'success',
             'bg-yellow-500/20 border-yellow-500/50': type === 'warning',
             'bg-blue-500/20 border-blue-500/50': type === 'info'
         }">
        <div class="flex items-start space-x-2 sm:space-x-3">
            <!-- Icon -->
            <div class="flex-shrink-0"
                 :class="{
                     'text-red-400': type === 'error',
                     'text-green-400': type === 'success',
                     'text-yellow-400': type === 'warning',
                     'text-blue-400': type === 'info'
                 }">
                <span x-show="type === 'error'">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'x','class' => 'w-5 h-5 sm:w-6 sm:h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5 sm:w-6 sm:h-6']); ?>
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
                </span>
                <span x-show="type === 'success'">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-5 h-5 sm:w-6 sm:h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-5 h-5 sm:w-6 sm:h-6']); ?>
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
                </span>
                <span x-show="type === 'warning'">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'warning','class' => 'w-5 h-5 sm:w-6 sm:h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'warning','class' => 'w-5 h-5 sm:w-6 sm:h-6']); ?>
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
                </span>
                <span x-show="type === 'info'">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'info','class' => 'w-5 h-5 sm:w-6 sm:h-6']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'info','class' => 'w-5 h-5 sm:w-6 sm:h-6']); ?>
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
                </span>
            </div>
            
            <!-- Message -->
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm sm:text-base break-words"
                   :class="{
                       'text-red-400': type === 'error',
                       'text-green-400': type === 'success',
                       'text-yellow-400': type === 'warning',
                       'text-blue-400': type === 'info'
                   }"
                   x-text="message"></p>
            </div>
            
            <!-- Close Button -->
            <button x-on:click="close()" 
                    class="flex-shrink-0 text-white/60 hover:text-white transition-colors touch-target">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
// Global toast manager to prevent duplicates
if (!window._toastManager) {
    window._toastManager = {
        instance: null,
        isShowing: false,
        handler: null
    };
}

function toast() {
    const manager = window._toastManager;
    
    // If instance already exists, reuse it
    if (manager.instance) {
        return manager.instance;
    }
    
    const instance = {
        visible: false,
        message: '',
        type: 'info',
        timeout: null,
        
        init() {
            // Store this instance globally
            manager.instance = this;
            
            // Remove any existing listeners to prevent duplicates
            if (manager.handler) {
                window.removeEventListener('toast', manager.handler);
            }
            
            // Create single handler
            const handler = (e) => {
                // Prevent duplicate shows
                if (manager.isShowing) {
                    return;
                }
                // Skip success type to prevent duplicate with alert components
                // Success messages are shown via alert components, not toast
                if (e.detail.type === 'success') {
                    return;
                }
                this.show(e.detail.message, e.detail.type || 'info');
            };
            
            manager.handler = handler;
            window.addEventListener('toast', handler, { once: false });
        },
        
        show(message, type = 'info') {
            // Prevent duplicate
            if (manager.isShowing && this.visible) {
                return;
            }
            
            manager.isShowing = true;
            this.message = message;
            this.type = type;
            this.visible = true;
            
            // Auto hide after 5 seconds
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            
            this.timeout = setTimeout(() => {
                this.close();
            }, 5000);
        },
        
        close() {
            this.visible = false;
            manager.isShowing = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
        }
    };
    
    return instance;
}
</script>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/toast.blade.php ENDPATH**/ ?>