

<?php $__env->startSection('title', 'Chat dengan ' . $otherUser->name . ' - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-4xl">
    <!-- Chat Header -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 border border-white/10">
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('chat.index')); ?>" 
               class="p-2 glass glass-hover rounded-lg transition-colors">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-left','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-left','class' => 'w-5 h-5']); ?>
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
            
            <div class="relative flex-shrink-0">
                <img src="<?php echo e($otherUser->avatar ? asset('storage/' . $otherUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name)); ?>" 
                     alt="<?php echo e($otherUser->name); ?>" 
                     class="w-12 h-12 sm:w-14 sm:h-14 rounded-full border-2 border-white/10">
                <?php if($otherUser->updated_at && $otherUser->updated_at->gt(now()->subMinutes(5))): ?>
                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-gray-900 rounded-full"></span>
                <?php endif; ?>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-semibold truncate"><?php echo e($otherUser->name); ?></h1>
                    <?php if($otherUser->isSeller() || $otherUser->isAdmin()): ?>
                    <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <?php endif; ?>
                </div>
                <?php if($otherUser->updated_at && $otherUser->updated_at->gt(now()->subMinutes(5))): ?>
                <p class="text-sm text-green-400 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Sedang Aktif
                </p>
                <?php elseif($otherUser->updated_at): ?>
                <p class="text-sm text-white/60">Terakhir aktif <?php echo e($otherUser->updated_at->diffForHumans()); ?></p>
                <?php endif; ?>
            </div>

            <?php if($otherUser->store_slug): ?>
            <a href="<?php echo e(route('store.show', $otherUser->store_slug)); ?>" 
               class="px-4 py-2 glass glass-hover rounded-lg border border-primary/30 bg-primary/20 text-primary hover:bg-primary/30 hover:border-primary/50 transition-all font-semibold text-sm flex items-center gap-2">
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
                <span class="hidden sm:inline">Toko</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="glass p-4 sm:p-6 rounded-xl mb-4 border border-white/10" 
         id="messages-container" 
         data-chat-id="<?php echo e($chat->id); ?>"
         data-current-user-id="<?php echo e($currentUser->id); ?>"
         data-username="<?php echo e($otherUser->username); ?>"
         style="max-height: 60vh; overflow-y: auto; scroll-behavior: smooth;">
        <?php if($messages->isEmpty()): ?>
        <div class="text-center py-12">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chat','class' => 'w-16 h-16 mx-auto mb-4 text-white/40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chat','class' => 'w-16 h-16 mx-auto mb-4 text-white/40']); ?>
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
            <p class="text-white/60 text-lg font-semibold mb-2">Belum ada pesan</p>
            <p class="text-white/40 text-sm">Mulai percakapan dengan mengirim pesan pertama!</p>
        </div>
        <?php else: ?>
        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('chat.partials.message', ['message' => $message], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>

    <!-- File Preview Container -->
    <div id="file-preview" class="hidden mb-4 glass p-3 rounded-xl border border-white/10"></div>

    <!-- Message Form -->
    <form id="chat-form" 
          action="<?php echo e(route('chat.send', $otherUser->username)); ?>" 
          method="POST" 
          enctype="multipart/form-data" 
          class="glass p-4 sm:p-6 rounded-xl border border-white/10">
        <?php echo csrf_field(); ?>
        
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- File Input (Hidden) -->
            <input type="file" 
                   name="attachment" 
                   id="attachment-input" 
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.jpg,.jpeg,.png,.gif,.webp"
                   class="hidden">
            
            <!-- Attachment Button -->
            <button type="button" 
                    onclick="document.getElementById('attachment-input').click()"
                    class="px-4 py-3 glass glass-hover rounded-lg transition-colors flex-shrink-0 flex items-center justify-center">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-5 h-5']); ?>
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
            
            <!-- Message Input -->
            <textarea name="message" 
                      id="message-input"
                      rows="1"
                      placeholder="Ketik pesan..." 
                      class="flex-1 px-4 py-3 glass border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
            
            <!-- Send Button -->
            <button type="submit" 
                    id="send-button"
                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-colors flex-shrink-0 flex items-center justify-center shadow-lg hover:shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- CRITICAL: Prevent default form submission IMMEDIATELY -->
<!-- This ensures form doesn't submit before JavaScript loads -->
<script>
(function() {
    'use strict';
    
    // Get form immediately (don't wait for DOMContentLoaded)
    const chatForm = document.getElementById('chat-form');
    
    if (chatForm) {
        // CRITICAL: Prevent default submission immediately
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            console.log('🚫 Default form submission prevented (inline script)');
            return false;
        }, { capture: true, once: false });
        
        // Also set onsubmit as fallback
        chatForm.onsubmit = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🚫 Default form submission prevented (onsubmit)');
            return false;
        };
        
        console.log('✅ Inline form protection attached');
    } else {
        console.warn('⚠️ Chat form not found in inline script');
    }
})();
</script>

<!-- Chat module will be loaded via app.js -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('📱 Chat page loaded with username: {{ $otherUser->username }}');
    
    // Verify ChatHandler is initialized
    if (window.chatHandler) {
        console.log('✅ ChatHandler is initialized');
    } else {
        console.error('❌ ChatHandler is NOT initialized!');
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/chat/show.blade.php ENDPATH**/ ?>