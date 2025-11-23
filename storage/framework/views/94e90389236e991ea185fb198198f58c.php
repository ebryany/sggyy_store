
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['order']));

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

foreach (array_filter((['order']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = auth()->user();
    $isSeller = ($order->type === 'product' && $order->product && $order->product->user_id === $user->id) ||
                ($order->type === 'service' && $order->service && $order->service->user_id === $user->id);
    $canManage = $isSeller || $user->isAdmin();
?>

<?php if($canManage && in_array($order->status, ['paid', 'processing', 'waiting_confirmation', 'needs_revision'])): ?>
<div class="glass p-4 sm:p-6 rounded-lg mb-6 border-2 border-primary/30" x-data="orderControls">
    <h3 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'target','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'target','class' => 'w-6 h-6 text-primary']); ?>
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
        Order Management
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Progress Control (only for processing/paid status) -->
        <?php if(in_array($order->status, ['paid', 'processing'])): ?>
        <div>
            <label class="block text-sm font-medium mb-2">Progress Pengerjaan</label>
            <div class="flex items-center gap-3">
                <input type="range" 
                       x-model="progress"
                       min="0" 
                       max="100" 
                       step="5"
                       class="flex-1 h-2 bg-white/10 rounded-lg appearance-none cursor-pointer accent-primary"
                       @change="updateProgress">
                <span class="text-lg font-bold text-primary w-12 text-right" x-text="progress + '%'"></span>
            </div>
            
            <!-- Progress Bar Visual -->
            <div class="mt-3 h-3 glass rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-primary to-blue-500 transition-all duration-500" 
                     :style="'width: ' + progress + '%'"></div>
            </div>
            
            <button @click="saveProgress" 
                    :disabled="saving"
                    class="mt-3 w-full px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold disabled:opacity-50 text-sm">
                <span x-show="!saving" class="flex items-center justify-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'save','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'save','class' => 'w-4 h-4']); ?>
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
                    Update Progress
                </span>
                <span x-show="saving" class="flex items-center justify-center">
                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
            
            <?php if($order->progress === 100 && $order->status !== 'completed'): ?>
            <p class="text-xs text-green-400 mt-2 flex items-center gap-1">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-3 h-3']); ?>
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
                Progress 100% akan otomatis menyelesaikan order
            </p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Deadline Control (only for processing/paid status) -->
        <?php if(in_array($order->status, ['paid', 'processing'])): ?>
        <div>
            <label class="block text-sm font-medium mb-2">Set Deadline</label>
            
            <?php if($order->deadline_at): ?>
            <div class="glass p-3 rounded-lg mb-3">
                <p class="text-xs text-white/60 mb-1">Current Deadline:</p>
                <p class="font-semibold text-sm flex items-center gap-2
                    <?php echo e($order->deadline_at->isPast() ? 'text-red-400' : ($order->deadline_at->diffInHours() < 24 ? 'text-yellow-400' : 'text-white')); ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <?php echo e($order->deadline_at->format('d M Y, H:i')); ?>

                    <span class="text-xs">(<?php echo e($order->deadline_at->diffForHumans()); ?>)</span>
                </p>
            </div>
            <?php endif; ?>
            
            <input type="datetime-local" 
                   x-model="deadline"
                   :min="minDeadline"
                   class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
            
            <button @click="saveDeadline" 
                    :disabled="savingDeadline || !deadline"
                    class="mt-3 w-full px-4 py-2 glass glass-hover rounded-lg transition-all font-semibold disabled:opacity-50 text-sm border border-white/20">
                <span x-show="!savingDeadline" class="flex items-center justify-center gap-2">
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
                    Set Deadline
                </span>
                <span x-show="savingDeadline" class="flex items-center justify-center">
                    <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                </span>
            </button>
        </div>
        <?php endif; ?>
        </div>
    
    <!-- Upload Deliverable (Seller only, for service orders) -->
    <?php if($order->type === 'service' && in_array($order->status, ['processing', 'waiting_confirmation', 'needs_revision', 'paid'])): ?>
    <div class="mt-6 pt-6 border-t border-white/10 <?php echo e(in_array($order->status, ['waiting_confirmation', 'needs_revision']) ? 'bg-orange-500/5 p-4 rounded-lg border-orange-500/30' : ''); ?>">
        <h4 class="text-base font-semibold mb-3 flex items-center gap-2">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'package','class' => 'w-5 h-5 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'package','class' => 'w-5 h-5 text-primary']); ?>
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
            Upload Hasil Pekerjaan
            <?php if(in_array($order->status, ['waiting_confirmation', 'needs_revision'])): ?>
                <span class="ml-2 px-2 py-0.5 bg-orange-500/20 text-orange-400 text-xs rounded border border-orange-500/30 font-semibold">⚠️ Revisi Diperlukan</span>
            <?php endif; ?>
        </h4>
        
        <?php if(in_array($order->status, ['waiting_confirmation', 'needs_revision'])): ?>
        <div class="mb-4 p-3 bg-orange-500/10 border border-orange-500/30 rounded-lg">
            <p class="text-sm text-orange-300 flex items-center gap-2">
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
                Buyer meminta revisi. Silakan upload hasil pekerjaan yang sudah diperbaiki.
            </p>
        </div>
        <?php endif; ?>
        
        <?php if($order->deliverable_path): ?>
        <div class="glass p-3 rounded-lg mb-3 bg-green-500/10 border border-green-500/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
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
                    <div>
                        <p class="font-semibold text-sm text-green-400">File sudah diupload</p>
                        <p class="text-xs text-white/60"><?php echo e(basename($order->deliverable_path)); ?></p>
                    </div>
                </div>
                <a href="<?php echo e(route('orders.downloadDeliverable', $order)); ?>" 
                   target="_blank"
                   class="px-3 py-1 bg-green-500/20 text-green-400 hover:bg-green-500/30 rounded text-xs font-semibold transition-colors">
                    📥 Lihat
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('orders.uploadDeliverable', $order)); ?>" 
              enctype="multipart/form-data"
              x-data="{ uploading: false, fileName: '' }"
              @submit.prevent="
                  uploading = true;
                  const formData = new FormData($el);
                  fetch('<?php echo e(route('orders.uploadDeliverable', $order)); ?>', {
                      method: 'POST',
                      headers: {
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                          'Accept': 'application/json'
                      },
                      body: formData
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.success || !data.errors) {
                          window.dispatchEvent(new CustomEvent('toast', { 
                              detail: { message: data.message || 'File berhasil diupload!', type: 'success' } 
                          }));
                          setTimeout(() => window.location.reload(), 1000);
                      } else {
                          throw new Error(data.message || Object.values(data.errors || {})[0]?.[0] || 'Upload gagal');
                      }
                  })
                  .catch(error => {
                      window.dispatchEvent(new CustomEvent('toast', { 
                          detail: { message: error.message || 'Upload gagal. Silakan coba lagi.', type: 'error' } 
                      }));
                      uploading = false;
                  });
              ">
            <?php echo csrf_field(); ?>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-2">File Hasil Pekerjaan</label>
                    <input type="file" 
                           name="deliverable" 
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt"
                           @change="fileName = $event.target.files[0]?.name || ''"
                           required
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm cursor-pointer"
                           :disabled="uploading">
                    <p class="text-xs text-white/60 mt-1">
                        Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, TXT (Max 10MB)
                    </p>
                    <p x-show="fileName" class="text-xs text-primary mt-1" x-text="'File: ' + fileName"></p>
                    <?php if($order->deliverable_path): ?>
                    <p class="text-xs text-yellow-400 mt-2">
                        <span class="flex items-center gap-1">
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
                            File hasil pekerjaan sudah ada. Upload file baru akan mengganti file yang lama.
                        </span>
                    </p>
                    <?php endif; ?>
                </div>
                
                <button type="submit" 
                        :disabled="uploading"
                        class="w-full px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold disabled:opacity-50 text-sm cursor-pointer">
                    <span x-show="!uploading">
                        <?php if($order->deliverable_path): ?>
                            🔄 Update Hasil Pekerjaan
                        <?php else: ?>
                            📤 Upload Hasil Pekerjaan
                        <?php endif; ?>
                    </span>
                    <span x-show="uploading" class="flex items-center justify-center">
                        <svg class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    </span>
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('orderControls', () => ({
        progress: <?php echo e($order->progress ?? 0); ?>,
        deadline: '<?php echo e($order->deadline_at ? $order->deadline_at->format('Y-m-d\TH:i') : ''); ?>',
        saving: false,
        savingDeadline: false,
        minDeadline: new Date().toISOString().slice(0, 16),
        
        async updateProgress() {
            // Real-time visual update
        },
        
        async saveProgress() {
            if (this.saving) return;
            
            this.saving = true;
            
            try {
                const response = await fetch('<?php echo e(route('orders.updateProgress', $order)); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        progress: this.progress
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { message: data.message, type: 'success' } 
                    }));
                    
                    if (this.progress === 100) {
                        setTimeout(() => window.location.reload(), 1500);
                    }
                } else {
                    throw new Error(data.message || 'Failed to update progress');
                }
            } catch (error) {
                window.dispatchEvent(new CustomEvent('toast', { 
                    detail: { message: error.message || 'Terjadi kesalahan', type: 'error' } 
                }));
            } finally {
                this.saving = false;
            }
        },
        
        async saveDeadline() {
            if (this.savingDeadline || !this.deadline) return;
            
            this.savingDeadline = true;
            
            try {
                const response = await fetch('<?php echo e(route('orders.setDeadline', $order)); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        deadline_at: this.deadline
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.dispatchEvent(new CustomEvent('toast', { 
                        detail: { message: data.message, type: 'success' } 
                    }));
                    
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to set deadline');
                }
            } catch (error) {
                window.dispatchEvent(new CustomEvent('toast', { 
                    detail: { message: error.message || 'Terjadi kesalahan', type: 'error' } 
                }));
            } finally {
                this.savingDeadline = false;
            }
        }
    }));
});
</script>
<?php endif; ?>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/order-progress-control.blade.php ENDPATH**/ ?>