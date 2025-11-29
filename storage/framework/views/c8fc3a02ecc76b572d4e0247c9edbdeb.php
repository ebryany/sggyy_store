<?php $__env->startSection('title', 'Tarik Saldo - Seller Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-3">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'withdraw','class' => 'w-8 h-8 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'withdraw','class' => 'w-8 h-8 text-primary']); ?>
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
                Tarik Saldo
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Tarik saldo dari penjualan produk dan jasa Anda</p>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(session('success')): ?>
    <div class="glass p-4 rounded-lg bg-green-500/20 border border-green-500/30">
        <p class="text-green-400 font-semibold flex items-center gap-2">
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

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- Withdrawable Balance -->
        <div class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 hover:border-primary/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] group-hover:bg-primary/20 transition-all"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full blur-[40px] group-hover:bg-primary/20 transition-all"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
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
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-white/80 mb-1">Saldo Tersedia</h3>
                        <p class="text-2xl sm:text-3xl font-bold text-primary break-words">
                            Rp <?php echo e(number_format($withdrawableBalance, 0, ',', '.')); ?>

                        </p>
                    </div>
                </div>
                <p class="text-xs text-white/60">Siap untuk ditarik</p>
            </div>
        </div>

        <!-- Pending Earnings -->
        <div class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 hover:border-primary/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] group-hover:bg-primary/20 transition-all"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full blur-[40px] group-hover:bg-primary/20 transition-all"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
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
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-white/80 mb-1">Pending</h3>
                        <p class="text-2xl sm:text-3xl font-bold text-primary break-words">
                            Rp <?php echo e(number_format($pendingEarnings, 0, ',', '.')); ?>

                        </p>
                    </div>
                </div>
                <p class="text-xs text-white/60">Menunggu konfirmasi</p>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 hover:border-primary/50 transition-all duration-300">
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px] group-hover:bg-primary/20 transition-all"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full blur-[40px] group-hover:bg-primary/20 transition-all"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
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
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-white/80 mb-1">Total Pendapatan</h3>
                        <p class="text-2xl sm:text-3xl font-bold text-primary break-words">
                            Rp <?php echo e(number_format($withdrawableBalance + $pendingEarnings, 0, ',', '.')); ?>

                        </p>
                    </div>
                </div>
                <p class="text-xs text-white/60">Semua waktu</p>
            </div>
        </div>
    </div>

    <!-- Withdrawal Form -->
    <div class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px]"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full blur-[40px]"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'withdraw','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'withdraw','class' => 'w-6 h-6 text-primary']); ?>
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
                <div>
                    <h2 class="text-xl font-semibold">Ajukan Penarikan Saldo</h2>
                    <p class="text-xs text-white/60">Isi form di bawah untuk mengajukan penarikan</p>
                </div>
            </div>

            <?php if($withdrawableBalance < $minWithdrawal): ?>
            <div class="mb-6 p-4 rounded-lg bg-white/5 border border-white/10">
                <p class="text-white/80 font-medium mb-1 flex items-center gap-2">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-4 h-4 text-yellow-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-4 h-4 text-yellow-400']); ?>
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
                    Saldo Tidak Mencukupi
                </p>
                <p class="text-sm text-white/60">
                    Minimum penarikan: Rp <?php echo e(number_format($minWithdrawal, 0, ',', '.')); ?>. 
                    Saldo tersedia: Rp <?php echo e(number_format($withdrawableBalance, 0, ',', '.')); ?>

                </p>
            </div>
            <?php endif; ?>

        <form method="POST" action="<?php echo e(route('seller.withdrawal.store')); ?>" 
              x-data="{ 
                  method: '<?php echo e(old('method', 'bank_transfer')); ?>',
                  amount: '<?php echo e(old('amount', '')); ?>',
                  withdrawableBalance: <?php echo e($withdrawableBalance); ?>,
                  minWithdrawal: <?php echo e($minWithdrawal); ?>,
                  maxWithdrawal: <?php echo e($maxWithdrawal); ?>,
                  isSubmitting: false,
                  get isValidAmount() {
                      if (!this.amount || this.amount === '') {
                          return false;
                      }
                      const amt = parseFloat(this.amount) || 0;
                      if (amt === 0) {
                          return false;
                      }
                      return amt >= this.minWithdrawal && amt <= this.maxWithdrawal && amt <= this.withdrawableBalance;
                  },
                  init() {
                      this.method = '<?php echo e(old('method', 'bank_transfer')); ?>';
                  },
                  handleSubmit(e) {
                      if (this.withdrawableBalance < this.minWithdrawal) {
                          e.preventDefault();
                          return false;
                      }
                      this.isSubmitting = true;
                      return true;
                  }
              }"
              @submit="handleSubmit">
            <?php echo csrf_field(); ?>
            
                <!-- Error Messages -->
                <?php if($errors->any()): ?>
                <div class="mb-6 p-4 rounded-lg bg-white/5 border border-white/10">
                    <p class="text-white/80 font-medium mb-2 flex items-center gap-2">
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'alert','class' => 'w-4 h-4 text-red-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'alert','class' => 'w-4 h-4 text-red-400']); ?>
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
                        Terjadi Kesalahan
                    </p>
                    <ul class="text-sm text-white/60 space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>• <?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <?php endif; ?>
            
            <div class="space-y-5">
                <!-- Amount -->
                <div>
                    <label class="block text-sm font-medium mb-2 text-white/80">Jumlah Penarikan</label>
                    <div class="relative">
                        <input type="number" 
                               name="amount" 
                               x-model="amount"
                               value="<?php echo e(old('amount')); ?>"
                               :min="minWithdrawal"
                               :max="Math.min(maxWithdrawal, withdrawableBalance)"
                               step="1000"
                               placeholder="Masukkan jumlah"
                               :disabled="withdrawableBalance < minWithdrawal || isSubmitting"
                               class="w-full border rounded-lg px-4 py-3 bg-white/5 focus:outline-none focus:border-primary text-base disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               required>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-white/60">
                        <span>
                            Min: Rp <?php echo e(number_format($minWithdrawal, 0, ',', '.')); ?> • 
                            Max: Rp <?php echo e(number_format(min($maxWithdrawal, $withdrawableBalance), 0, ',', '.')); ?>

                        </span>
                        <button type="button" 
                                @click="amount = withdrawableBalance"
                                :disabled="withdrawableBalance < minWithdrawal || isSubmitting"
                                class="text-primary hover:underline disabled:opacity-50 disabled:cursor-not-allowed">
                            Tarik Semua
                        </button>
                    </div>
                    <p x-show="amount && parseFloat(amount) > 0 && (parseFloat(amount) < minWithdrawal || parseFloat(amount) > maxWithdrawal || parseFloat(amount) > withdrawableBalance || parseFloat(amount) % 1000 !== 0)" 
                       x-cloak
                       class="mt-2 text-xs text-red-400">
                        <span x-show="parseFloat(amount) < minWithdrawal" class="block">
                            Minimum: Rp <?php echo e(number_format($minWithdrawal, 0, ',', '.')); ?>

                        </span>
                        <span x-show="parseFloat(amount) > maxWithdrawal" class="block">
                            Maksimum: Rp <?php echo e(number_format($maxWithdrawal, 0, ',', '.')); ?>

                        </span>
                        <span x-show="parseFloat(amount) > withdrawableBalance" class="block">
                            Saldo tidak mencukupi
                        </span>
                        <span x-show="parseFloat(amount) >= minWithdrawal && parseFloat(amount) <= maxWithdrawal && parseFloat(amount) <= withdrawableBalance && parseFloat(amount) % 1000 !== 0" class="block">
                            Harus kelipatan Rp 1.000
                        </span>
                    </p>
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-xs text-red-400">
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Method -->
                <div>
                    <label class="block text-sm font-medium mb-3 text-white/80">Metode Penarikan</label>
                    <?php $__errorArgs = ['method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mb-2 text-xs text-red-400">
                        <?php echo e($message); ?>

                    </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center p-4 rounded-lg cursor-pointer border transition-colors"
                               :class="method === 'bank_transfer' ? 'border-primary/50 bg-primary/5' : 'border-white/10 bg-white/5 hover:border-white/20'">
                            <input type="radio" 
                                   name="method" 
                                   value="bank_transfer" 
                                   x-model="method"
                                   :disabled="isSubmitting"
                                   class="mr-3 accent-primary disabled:opacity-50"
                                   <?php if(old('method') === 'bank_transfer'): ?> checked <?php endif; ?>>
                            <div class="flex-1">
                                <span class="font-medium block flex items-center gap-2 text-white/90">
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
                                    Transfer Bank
                                </span>
                                <span class="text-xs text-white/50">Rekening bank</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 rounded-lg cursor-pointer border transition-colors"
                               :class="method === 'e_wallet' ? 'border-primary/50 bg-primary/5' : 'border-white/10 bg-white/5 hover:border-white/20'">
                            <input type="radio" 
                                   name="method" 
                                   value="e_wallet" 
                                   x-model="method"
                                   :disabled="isSubmitting"
                                   class="mr-3 accent-primary disabled:opacity-50"
                                   <?php if(old('method') === 'e_wallet'): ?> checked <?php endif; ?>>
                            <div class="flex-1">
                                <span class="font-medium block flex items-center gap-2 text-white/90">
                                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mobile','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','class' => 'w-4 h-4']); ?>
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
                                    E-Wallet
                                </span>
                                <span class="text-xs text-white/50">Dana, OVO, GoPay</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Bank Transfer Fields -->
                <div x-show="method === 'bank_transfer'" 
                     x-cloak
                     class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-white/80">Nama Bank</label>
                        <input type="text" 
                               name="bank_name" 
                               value="<?php echo e(old('bank_name')); ?>"
                               placeholder="Contoh: Bank BCA, Bank Mandiri"
                               x-bind:required="method === 'bank_transfer'"
                               :disabled="isSubmitting"
                               class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-400">
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white/80">Nomor Rekening</label>
                            <input type="text" 
                                   name="account_number" 
                                   value="<?php echo e(old('account_number')); ?>"
                                   placeholder="1234567890"
                                   x-bind:required="method === 'bank_transfer'"
                                   :disabled="isSubmitting"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-red-400">
                                <?php echo e($message); ?>

                            </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white/80">Atas Nama</label>
                            <input type="text" 
                                   name="account_name" 
                                   value="<?php echo e(old('account_name')); ?>"
                                   placeholder="Nama pemilik rekening"
                                   x-bind:required="method === 'bank_transfer'"
                                   :disabled="isSubmitting"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-xs text-red-400">
                                <?php echo e($message); ?>

                            </p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- E-Wallet Fields -->
                <div x-show="method === 'e_wallet'" 
                     x-cloak
                     class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2 text-white/80">Jenis E-Wallet</label>
                        <select name="e_wallet_type" 
                                x-bind:required="method === 'e_wallet'"
                                :disabled="isSubmitting"
                                class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['e_wallet_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Pilih E-Wallet</option>
                            <option value="dana" <?php if(old('e_wallet_type') === 'dana'): ?> selected <?php endif; ?>>DANA</option>
                            <option value="ovo" <?php if(old('e_wallet_type') === 'ovo'): ?> selected <?php endif; ?>>OVO</option>
                            <option value="gopay" <?php if(old('e_wallet_type') === 'gopay'): ?> selected <?php endif; ?>>GoPay</option>
                            <option value="linkaja" <?php if(old('e_wallet_type') === 'linkaja'): ?> selected <?php endif; ?>>LinkAja</option>
                        </select>
                        <?php $__errorArgs = ['e_wallet_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-400">
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 text-white/80">Nomor E-Wallet</label>
                        <input type="text" 
                               name="e_wallet_number" 
                               value="<?php echo e(old('e_wallet_number')); ?>"
                               placeholder="08xxxxxxxxxx"
                               x-bind:required="method === 'e_wallet'"
                               :disabled="isSubmitting"
                               class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed <?php $__errorArgs = ['e_wallet_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500/50 <?php else: ?> border-white/10 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['e_wallet_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-1 text-xs text-red-400">
                            <?php echo e($message); ?>

                        </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 mt-6 border-t border-white/10">
                    <button type="submit" 
                            :disabled="withdrawableBalance < minWithdrawal || isSubmitting"
                            class="w-full px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <template x-if="!isSubmitting">
                            <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'withdraw','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'withdraw','class' => 'w-4 h-4']); ?>
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
                        <template x-if="isSubmitting">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                        <span x-text="isSubmitting ? 'Memproses...' : 'Ajukan Penarikan'"></span>
                    </button>
                    <p class="mt-3 text-xs text-white/50 text-center">
                        Jumlah harus kelipatan Rp 1.000
                    </p>
                </div>
            </div>
        </form>
        </div>
    </div>

    <!-- Withdrawal History -->
    <div class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-[60px]"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-primary/10 rounded-full blur-[40px]"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'history','class' => 'w-6 h-6 text-primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'history','class' => 'w-6 h-6 text-primary']); ?>
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
                <div>
                    <h2 class="text-xl font-semibold">Riwayat Penarikan</h2>
                    <p class="text-xs text-white/60">Daftar semua penarikan saldo yang pernah dilakukan</p>
                </div>
            </div>

            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 rounded-lg border border-white/10 bg-white/5">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                <?php if($withdrawal->status === 'completed'): ?> bg-green-500/20 text-green-400
                                <?php elseif($withdrawal->status === 'processing'): ?> bg-blue-500/20 text-blue-400
                                <?php elseif($withdrawal->status === 'rejected'): ?> bg-red-500/20 text-red-400
                                <?php else: ?> bg-yellow-500/20 text-yellow-400
                                <?php endif; ?>">
                                <?php echo e(ucfirst($withdrawal->status)); ?>

                            </span>
                            <p class="text-sm font-medium text-white/60">
                                <?php echo e($withdrawal->reference_number); ?>

                            </p>
                        </div>
                        <p class="text-xs text-white/50 mb-1">
                            <?php if($withdrawal->method === 'bank_transfer'): ?>
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'bank','class' => 'w-3 h-3 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bank','class' => 'w-3 h-3 inline mr-1']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'mobile','class' => 'w-3 h-3 inline mr-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'mobile','class' => 'w-3 h-3 inline mr-1']); ?>
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
                            <?php echo e($withdrawal->bank_name ?? ucfirst($withdrawal->method)); ?>

                            <?php if($withdrawal->account_number): ?>
                                • <?php echo e($withdrawal->account_number); ?>

                            <?php endif; ?>
                        </p>
                        <p class="text-xs text-white/50">
                            <?php echo e($withdrawal->created_at->format('d M Y, H:i')); ?>

                        </p>
                        <?php if($withdrawal->rejection_reason): ?>
                        <p class="text-xs text-red-400 mt-2">
                            Alasan ditolak: <?php echo e($withdrawal->rejection_reason); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="text-left sm:text-right flex-shrink-0">
                        <p class="font-semibold text-primary text-lg">
                            Rp <?php echo e(number_format($withdrawal->amount, 0, ',', '.')); ?>

                        </p>
                        <?php if($withdrawal->processed_at): ?>
                        <p class="text-xs text-white/50 mt-1">
                            Diproses: <?php echo e($withdrawal->processed_at->format('d M Y')); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-12">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'withdraw','class' => 'w-12 h-12 text-white/20 mx-auto mb-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'withdraw','class' => 'w-12 h-12 text-white/20 mx-auto mb-3']); ?>
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
                    <p class="text-white/50 text-sm">Belum ada riwayat penarikan</p>
                </div>
                <?php endif; ?>
            </div>

            <?php if($withdrawals->hasPages()): ?>
            <div class="mt-6">
                <?php echo e($withdrawals->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('seller.layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/seller/withdrawal/index.blade.php ENDPATH**/ ?>