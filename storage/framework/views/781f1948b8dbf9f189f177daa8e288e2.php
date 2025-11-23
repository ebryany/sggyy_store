

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
         id="messagesContainer" 
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
        <div class="space-y-4">
            <?php
                $lastDate = null;
            ?>
            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $currentDate = $message->created_at->format('Y-m-d');
                    $showDateSeparator = $lastDate !== $currentDate;
                    $lastDate = $currentDate;
                ?>
                
                <?php if($showDateSeparator): ?>
                <div class="flex items-center justify-center my-4">
                    <div class="flex items-center gap-2 px-3 py-1 glass rounded-full">
                        <span class="text-xs text-white/60">
                            <?php if($message->created_at->isToday()): ?>
                                Hari Ini
                            <?php elseif($message->created_at->isYesterday()): ?>
                                Kemarin
                            <?php else: ?>
                                <?php echo e($message->created_at->format('d M Y')); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="flex <?php echo e($message->isFromCurrentUser() ? 'justify-end' : 'justify-start'); ?> group">
                    <div class="max-w-[75%] sm:max-w-[60%] lg:max-w-[50%]">
                        <?php if(!$message->isFromCurrentUser()): ?>
                        <div class="flex items-center gap-2 mb-1 px-1">
                            <img src="<?php echo e($message->sender->avatar ? asset('storage/' . $message->sender->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->name)); ?>" 
                                 alt="<?php echo e($message->sender->name); ?>" 
                                 class="w-5 h-5 rounded-full border border-white/20">
                            <span class="text-xs text-white/60 font-medium"><?php echo e($message->sender->name); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="p-3 sm:p-4 rounded-xl shadow-lg hover:shadow-xl transition-shadow <?php echo e($message->isFromCurrentUser() ? 'bg-primary text-white rounded-tr-sm' : 'bg-white/10 text-white rounded-tl-sm'); ?>">
                            <?php if($message->message): ?>
                            <p class="text-sm sm:text-base whitespace-pre-wrap break-words leading-relaxed"><?php echo e($message->message); ?></p>
                            <?php endif; ?>
                            
                            <?php if($message->attachment_path): ?>
                            <div class="mt-3">
                                <?php
                                    $attachmentUrl = $message->getAttachmentUrl();
                                    $extension = strtolower(pathinfo($message->attachment_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                ?>
                                
                                <?php if($isImage): ?>
                                <div class="rounded-lg overflow-hidden border-2 border-white/20 hover:border-white/30 transition-colors">
                                    <a href="<?php echo e($attachmentUrl); ?>" target="_blank" class="block">
                                        <img src="<?php echo e($attachmentUrl); ?>" 
                                             alt="Attachment" 
                                             class="max-w-full h-auto max-h-64 object-contain cursor-pointer hover:opacity-90 transition-opacity"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display:none;" class="flex items-center gap-2 px-3 py-2 bg-white/20 hover:bg-white/30 rounded-lg">
                                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-4 h-4']); ?>
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
                                            <span class="text-sm">Gambar tidak dapat dimuat</span>
                                        </div>
                                    </a>
                                </div>
                                <a href="<?php echo e($attachmentUrl); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 mt-2 px-3 py-1.5 <?php echo e($message->isFromCurrentUser() ? 'bg-white/20 hover:bg-white/30' : 'bg-white/10 hover:bg-white/20'); ?> rounded-lg transition-colors text-xs">
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
                                    Buka di tab baru
                                </a>
                                <?php else: ?>
                                <a href="<?php echo e($attachmentUrl); ?>" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-2 px-3 py-2 <?php echo e($message->isFromCurrentUser() ? 'bg-white/20 hover:bg-white/30' : 'bg-white/10 hover:bg-white/20'); ?> rounded-lg transition-colors text-sm">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'file-text','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'file-text','class' => 'w-4 h-4']); ?>
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
                                    <span><?php echo e(basename($message->attachment_path)); ?></span>
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'w-3 h-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'w-3 h-3']); ?>
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
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center <?php echo e($message->isFromCurrentUser() ? 'justify-end' : 'justify-start'); ?> gap-2 mt-2">
                                <span class="text-xs opacity-70"><?php echo e($message->created_at->format('H:i')); ?></span>
                                <?php if($message->isFromCurrentUser()): ?>
                                    <?php if($message->is_read): ?>
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-4 h-4 opacity-70']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4 opacity-70']); ?>
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
                                    <?php else: ?>
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'check','class' => 'w-4 h-4 opacity-40']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4 opacity-40']); ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="glass p-4 rounded-xl mb-4 border border-green-500/30 bg-green-500/20">
        <p class="text-green-400 flex items-center gap-2">
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
            <?php echo e(session('success')); ?>

        </p>
    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="glass p-4 rounded-xl mb-4 border border-red-500/30 bg-red-500/20">
        <div class="flex items-start gap-2">
            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert-circle','class' => 'w-5 h-5 text-red-400 flex-shrink-0 mt-0.5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert-circle','class' => 'w-5 h-5 text-red-400 flex-shrink-0 mt-0.5']); ?>
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
                <p class="text-red-400 font-semibold mb-1">Terjadi kesalahan:</p>
                <ul class="text-red-300 text-sm space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>• <?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Message Form -->
    <form action="<?php echo e(route('chat.send', $otherUser->id)); ?>" 
          method="POST" 
          enctype="multipart/form-data" 
          class="glass p-4 sm:p-6 rounded-xl border border-white/10"
          id="messageForm"
          data-no-ajax="true"
          data-no-intercept="true"
          data-form-type="normal"
          onsubmit="return validateChatForm(this);">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="_no_ajax" value="1">
        <input type="hidden" name="_form_type" value="normal">
        
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- File Input (Hidden) -->
            <input type="file" 
                   name="attachment" 
                   id="attachmentInput" 
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.jpg,.jpeg,.png,.gif,.webp"
                   class="hidden">
            
            <!-- Attachment Button -->
            <button type="button" 
                    onclick="document.getElementById('attachmentInput').click()"
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
                      id="messageInput"
                      rows="1"
                      placeholder="Ketik pesan..." 
                      class="flex-1 px-4 py-3 glass border border-white/20 rounded-lg text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary resize-none"></textarea>
            
            <!-- Send Button -->
            <button type="submit" 
                    id="sendButton"
                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-colors flex-shrink-0 flex items-center justify-center shadow-lg hover:shadow-primary/20 disabled:opacity-50 disabled:cursor-not-allowed">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'w-5 h-5']); ?>
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
        
        <!-- File Preview & Name Display -->
        <div id="filePreviewContainer" class="mt-3 hidden">
            <div class="glass p-3 rounded-lg border border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div id="filePreview" class="flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p id="fileNameDisplay" class="text-sm text-white/80 font-medium truncate"></p>
                        <p id="fileSizeDisplay" class="text-xs text-white/60"></p>
                    </div>
                </div>
                <button type="button" 
                        onclick="clearAttachment()"
                        class="p-1 glass glass-hover rounded-lg transition-colors flex-shrink-0 ml-2">
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
                </button>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // CRITICAL: Inline validation - NO AJAX, NO fetch, NO intercept
    // This function is called by form's onsubmit attribute
    function validateChatForm(form) {
        const messageInput = form.querySelector('textarea[name="message"]');
        const attachmentInput = form.querySelector('input[name="attachment"]');
        
        const hasMessage = messageInput && messageInput.value.trim().length > 0;
        const hasAttachment = attachmentInput && attachmentInput.files.length > 0;
        
        if (!hasMessage && !hasAttachment) {
            // Use toast instead of alert for better UX
            window.dispatchEvent(new CustomEvent('toast', { 
                detail: { 
                    message: 'Pesan atau file attachment wajib diisi', 
                    type: 'error' 
                } 
            }));
            return false;
        }
        
        // CRITICAL: Let form submit normally - browser will handle POST and follow redirect
        // DO NOT use fetch, axios, or any AJAX
        // DO NOT prevent default - browser must submit form normally
        return true;
    }
    
    // CRITICAL: Ensure form is marked BEFORE app.js runs
    // This prevents app.js from adding event listeners
    (function() {
        const form = document.getElementById('messageForm');
        if (form) {
            form.setAttribute('data-no-ajax', 'true');
            form.setAttribute('data-no-intercept', 'true');
        }
    })();
</script>
<style>
    /* SECURITY: Hide any JSON responses that might appear (should never happen, but safety measure) */
    body > pre:only-child,
    body > pre:first-child:last-child {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
    }
    
    /* Hide JSON if it appears as text content */
    body:has(> pre:only-child) {
        overflow: hidden;
    }
</style>
<script>
    // Auto-scroll to bottom with smooth behavior
    function scrollToBottom() {
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTo({
                top: messagesContainer.scrollHeight,
                behavior: 'smooth'
            });
        }
    }
    
    // Initial scroll
    setTimeout(scrollToBottom, 100);

    // Auto-resize textarea
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    
    function validateForm() {
        const hasMessage = messageInput && messageInput.value.trim().length > 0;
        const hasAttachment = attachmentInput && attachmentInput.files.length > 0;
        const isValid = hasMessage || hasAttachment;
        
        if (sendButton) {
            sendButton.disabled = !isValid;
        }
        
        return isValid;
    }
    
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            validateForm();
        });
        
        // Handle Enter key (submit on Enter, new line on Shift+Enter)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (validateForm()) {
                    document.getElementById('messageForm').submit();
                }
            }
        });
    }
    
    // Validate on attachment change
    if (attachmentInput) {
        attachmentInput.addEventListener('change', function() {
            validateForm();
        });
    }
    
    // Initial validation
    validateForm();

    // File preview and display
    const attachmentInput = document.getElementById('attachmentInput');
    const filePreviewContainer = document.getElementById('filePreviewContainer');
    const filePreview = document.getElementById('filePreview');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileSizeDisplay = document.getElementById('fileSizeDisplay');
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
    
    function clearAttachment() {
        if (attachmentInput) {
            attachmentInput.value = '';
        }
        if (filePreviewContainer) {
            filePreviewContainer.classList.add('hidden');
        }
        if (filePreview) {
            filePreview.innerHTML = '';
        }
    }
    
    if (attachmentInput && filePreviewContainer) {
        attachmentInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileName = file.name;
                const fileSize = formatFileSize(file.size);
                const fileType = file.type;
                
                // Display file name and size
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = fileName;
                }
                if (fileSizeDisplay) {
                    fileSizeDisplay.textContent = fileSize;
                }
                
                // Preview for images
                if (fileType.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (filePreview) {
                            filePreview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-12 h-12 object-cover rounded-lg border border-white/20">';
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (filePreview) {
                        filePreview.innerHTML = '<div class="w-12 h-12 glass rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>';
                    }
                }
                
                filePreviewContainer.classList.remove('hidden');
            } else {
                clearAttachment();
            }
        });
    }

    // Auto-refresh messages every 5 seconds (AJAX instead of full reload)
    let isRefreshing = false;
    setInterval(function() {
        if (document.visibilityState === 'visible' && !isRefreshing) {
            isRefreshing = true;
            fetch(window.location.href, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newMessagesContainer = doc.getElementById('messagesContainer');
                const currentMessagesContainer = document.getElementById('messagesContainer');
                
                if (newMessagesContainer && currentMessagesContainer) {
                    const wasAtBottom = currentMessagesContainer.scrollHeight - currentMessagesContainer.scrollTop <= currentMessagesContainer.clientHeight + 100;
                    currentMessagesContainer.innerHTML = newMessagesContainer.innerHTML;
                    
                    if (wasAtBottom) {
                        scrollToBottom();
                    }
                }
                isRefreshing = false;
            })
            .catch(error => {
                console.error('Error refreshing messages:', error);
                isRefreshing = false;
            });
        }
    }, 5000);
    
    // CRITICAL: NO JavaScript interference with form submit AT ALL
    // Form uses inline onsubmit validation - browser handles everything else
    // DO NOT add any event listeners to form or button
    // DO NOT use fetch, axios, or any AJAX
    // Let browser submit form normally and follow redirect from server
    
    // Reset form after successful submit (when page reloads with success message)
    <?php if(session('success')): ?>
    document.addEventListener('DOMContentLoaded', function() {
        if (messageInput) {
            messageInput.value = '';
            messageInput.style.height = 'auto';
        }
        clearAttachment();
        if (sendButton) {
            sendButton.disabled = false;
            sendButton.innerHTML = '<?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'arrow-right','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'arrow-right','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>';
        }
        validateForm();
        scrollToBottom();
    });
    <?php endif; ?>
    
    // SECURITY: Prevent JSON response from being displayed
    // If somehow JSON response appears, immediately redirect
    window.addEventListener('DOMContentLoaded', function() {
        // Check if page contains only JSON (should never happen)
        const bodyText = document.body.innerText.trim();
        if (bodyText.startsWith('{') && bodyText.endsWith('}') && bodyText.includes('"id"') && bodyText.includes('"sender"')) {
            // This is a JSON response - redirect immediately
            console.error('SECURITY: JSON response detected, redirecting...');
            window.location.href = '<?php echo e(route("chat.show", $otherUser->id)); ?>';
        }
    });
    
    // Auto-hide success/error messages after 5 seconds
    setTimeout(function() {
        const successMsg = document.querySelector('.bg-green-500\\/20');
        const errorMsg = document.querySelector('.bg-red-500\\/20');
        if (successMsg) {
            successMsg.style.transition = 'opacity 0.5s';
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 500);
        }
        if (errorMsg) {
            errorMsg.style.transition = 'opacity 0.5s';
            errorMsg.style.opacity = '0';
            setTimeout(() => errorMsg.remove(), 500);
        }
    }, 5000);
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/chat/show.blade.php ENDPATH**/ ?>