<?php $__env->startSection('title', 'Register - Ebrystoree'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <div class="w-full max-w-md">
        <!-- Branding Header -->
        <div class="text-center mb-6 sm:mb-8">
            <?php
                $settingsService = app(\App\Services\SettingsService::class);
                $platformSettings = $settingsService->getPlatformSettings();
                $siteName = $platformSettings['site_name'] ?? 'Ebrystoree';
                $logo = $platformSettings['logo'] ?? '';
            ?>
            <?php if($logo): ?>
            <div class="flex justify-center mb-4">
                <img src="<?php echo e($logo); ?>" alt="<?php echo e($siteName); ?>" class="h-12 sm:h-16 w-auto">
            </div>
            <?php endif; ?>
            <h1 class="text-3xl sm:text-4xl font-bold mb-2 text-primary">
                Buat Akun Baru
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Bergabunglah dengan kami hari ini</p>
        </div>

        <!-- Register Form -->
        <div class="glass p-6 sm:p-8 rounded-xl shadow-2xl border border-white/10">
            <form method="POST" action="<?php echo e(route('register')); ?>" 
                  x-data="{ 
                      showPassword: false, 
                      showPasswordConfirmation: false,
                      password: '',
                      passwordStrength: 0,
                      checkPasswordStrength() {
                          let strength = 0;
                          if (this.password.length >= 8) strength++;
                          if (this.password.match(/[a-z]/) && this.password.match(/[A-Z]/)) strength++;
                          if (this.password.match(/[0-9]/)) strength++;
                          if (this.password.match(/[^a-zA-Z0-9]/)) strength++;
                          this.passwordStrength = strength;
                      }
                  }"
                  @input="if ($event.target.name === 'password') checkPasswordStrength()">
                <?php echo csrf_field(); ?>
                
                <!-- Name Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Nama Lengkap</label>
                    <div class="relative">
                        <input type="text" 
                               name="name" 
                               value="<?php echo e(old('name')); ?>" 
                               required
                               autocomplete="name"
                               placeholder="Masukkan nama lengkap Anda"
                               class="w-full glass border <?php echo e($errors->has('name') ? 'border-red-500 pr-12' : 'border-white/10 pr-4'); ?> rounded-lg px-4 py-3 bg-white/5 focus:outline-none <?php echo e($errors->has('name') ? 'focus:border-red-500 focus:ring-2 focus:ring-red-500/20' : 'focus:border-primary focus:ring-2 focus:ring-primary/20'); ?> transition-all text-base touch-target placeholder:text-white/60">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
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
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Email Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Email</label>
                    <div class="relative">
                        <input type="email" 
                               name="email" 
                               value="<?php echo e(old('email')); ?>" 
                               required
                               autocomplete="email"
                               placeholder="nama@email.com"
                               class="w-full glass border <?php echo e($errors->has('email') ? 'border-red-500 pr-12' : 'border-white/10 pr-4'); ?> rounded-lg px-4 py-3 bg-white/5 focus:outline-none <?php echo e($errors->has('email') ? 'focus:border-red-500 focus:ring-2 focus:ring-red-500/20' : 'focus:border-primary focus:ring-2 focus:ring-primary/20'); ?> transition-all text-base touch-target placeholder:text-white/60">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none z-10">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
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
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Password Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Password</label>
                    <div class="relative">
                        <input x-model="password"
                               :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required
                               autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="w-full glass border <?php echo e($errors->has('password') ? 'border-red-500 pr-12' : 'border-white/10 pr-12'); ?> rounded-lg px-4 py-3 bg-white/5 focus:outline-none <?php echo e($errors->has('password') ? 'focus:border-red-500 focus:ring-2 focus:ring-red-500/20' : 'focus:border-primary focus:ring-2 focus:ring-primary/20'); ?> transition-all text-base touch-target placeholder:text-white/60">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 z-10 flex items-center gap-2">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="pointer-events-none">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 6 6 18M6 6l12 12"/>
                                </svg>
                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <button type="button"
                                    @click="showPassword = !showPassword"
                                    class="text-white/70 hover:text-white transition-colors">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div x-show="password.length > 0" x-cloak class="mt-2">
                        <div class="flex gap-1 mb-1">
                            <div class="h-1 flex-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'bg-red-500': passwordStrength <= 1,
                                         'bg-yellow-500': passwordStrength === 2,
                                         'bg-blue-500': passwordStrength === 3,
                                         'bg-green-500': passwordStrength >= 4
                                     }"
                                     :style="`width: ${(passwordStrength / 4) * 100}%`"></div>
                            </div>
                            <div class="h-1 flex-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'bg-red-500': passwordStrength <= 1,
                                         'bg-yellow-500': passwordStrength === 2,
                                         'bg-blue-500': passwordStrength === 3,
                                         'bg-green-500': passwordStrength >= 4
                                     }"
                                     :style="`width: ${passwordStrength >= 2 ? ((passwordStrength - 1) / 3) * 100 : 0}%`"></div>
                            </div>
                            <div class="h-1 flex-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full transition-all duration-300"
                                     :class="{
                                         'bg-yellow-500': passwordStrength === 2,
                                         'bg-blue-500': passwordStrength === 3,
                                         'bg-green-500': passwordStrength >= 4
                                     }"
                                     :style="`width: ${passwordStrength >= 3 ? ((passwordStrength - 2) / 2) * 100 : 0}%`"></div>
                            </div>
                            <div class="h-1 flex-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full transition-all duration-300 bg-green-500"
                                     :style="`width: ${passwordStrength >= 4 ? 100 : 0}%`"></div>
                            </div>
                        </div>
                        <p class="text-xs text-white/50" 
                           :class="{
                               'text-red-400': passwordStrength <= 1,
                               'text-yellow-400': passwordStrength === 2,
                               'text-blue-400': passwordStrength === 3,
                               'text-green-400': passwordStrength >= 4
                           }">
                            <span x-show="passwordStrength === 0">Masukkan password</span>
                            <span x-show="passwordStrength === 1">Lemah</span>
                            <span x-show="passwordStrength === 2">Sedang</span>
                            <span x-show="passwordStrength === 3">Kuat</span>
                            <span x-show="passwordStrength >= 4">Sangat Kuat</span>
                        </p>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
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
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <!-- Password Confirmation Field -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2 text-white/90">Konfirmasi Password</label>
                    <div class="relative">
                        <input :type="showPasswordConfirmation ? 'text' : 'password'" 
                               name="password_confirmation" 
                               required
                               autocomplete="new-password"
                               placeholder="Ulangi password Anda"
                               class="w-full glass border border-white/10 rounded-lg px-4 pr-12 py-3 bg-white/5 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-base touch-target placeholder:text-white/60">
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 z-10">
                            <button type="button"
                                    @click="showPasswordConfirmation = !showPasswordConfirmation"
                                    class="text-white/70 hover:text-white transition-colors">
                                <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirmation" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-6">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               name="terms" 
                               required
                               class="mt-1 w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary focus:ring-2 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm text-white/70 group-hover:text-white transition-colors">
                            Saya menyetujui 
                            <a href="#" class="text-primary hover:underline">Syarat & Ketentuan</a> 
                            dan 
                            <a href="#" class="text-primary hover:underline">Kebijakan Privasi</a>
                        </span>
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-primary hover:bg-primary-dark rounded-lg px-4 py-3.5 transition-all touch-target text-base font-semibold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <span>Daftar Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-dark text-white/40">atau</span>
                </div>
            </div>
            
            <!-- Login Link -->
            <p class="text-center text-sm sm:text-base text-white/60">
                Sudah punya akun? 
                <a href="<?php echo e(route('login')); ?>" class="text-primary hover:text-primary-dark font-semibold transition-colors touch-target">
                    Masuk disini
                </a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/auth/register.blade.php ENDPATH**/ ?>