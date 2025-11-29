<?php $__env->startSection('title', 'Laporan Keuangan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 lg:py-8">
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Laporan Keuangan</h1>
                <p class="text-white/60 text-sm sm:text-base mt-1">Ringkasan penghasilan dan biaya platform</p>
            </div>
            
            <!-- Period Filter -->
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('admin.financial-report.index', ['period' => 'daily'])); ?>" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base <?php echo e($period === 'daily' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10'); ?>">
                    Harian
                </a>
                <a href="<?php echo e(route('admin.financial-report.index', ['period' => 'monthly'])); ?>" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base <?php echo e($period === 'monthly' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10'); ?>">
                    Bulanan
                </a>
                <a href="<?php echo e(route('admin.financial-report.index', ['period' => 'yearly'])); ?>" 
                   class="px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-sm sm:text-base <?php echo e($period === 'yearly' ? 'bg-primary text-white' : 'bg-white/5 text-white/70 hover:bg-white/10'); ?>">
                    Tahunan
                </a>
            </div>
        </div>

        <!-- Configuration Info -->
        <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
            <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">Konfigurasi Biaya</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Fee Platform</p>
                    <p class="text-white font-semibold text-sm sm:text-base">Rp <?php echo e(number_format($configuration['platform_fee_fixed'], 0, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya QRIS</p>
                    <p class="text-white font-semibold text-sm sm:text-base"><?php echo e($configuration['qris_fee_percent']); ?>%</p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya Payout (Fixed)</p>
                    <p class="text-white font-semibold text-sm sm:text-base">Rp <?php echo e(number_format($configuration['payout_fee_fixed'], 0, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-white/60 text-xs sm:text-sm">Biaya Payout (Percent)</p>
                    <p class="text-white font-semibold text-sm sm:text-base"><?php echo e($configuration['payout_fee_percent']); ?>%</p>
                </div>
            </div>
        </div>

        <?php if($report): ?>
            <!-- Transactions Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">📊 Transaksi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Transaksi</p>
                        <p class="text-white text-lg sm:text-xl font-bold"><?php echo e(number_format($report['transactions']['count'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Nilai Transaksi</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['transactions']['total_amount'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Biaya QRIS (5%)</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['transactions']['qris_fee'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Fee Platform</p>
                        <p class="text-green-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['transactions']['platform_fee'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Seller Earning</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['transactions']['seller_earning'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Dana Masuk Platform</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['transactions']['platform_revenue'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Withdrawals Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">💸 Payout Seller</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Payout</p>
                        <p class="text-white text-lg sm:text-xl font-bold"><?php echo e(number_format($report['withdrawals']['count'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Jumlah Payout</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['withdrawals']['total_amount'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payout</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['withdrawals']['payout_fee'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Dana Diterima Seller</p>
                        <p class="text-white text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['withdrawals']['seller_received'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">📈 Ringkasan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Penghasilan Platform (Net)</p>
                        <p class="text-green-400 text-xl sm:text-2xl font-bold">Rp <?php echo e(number_format($report['summary']['platform_net_income'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payment Gateway</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['summary']['total_payment_gateway_fee'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Payout</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['summary']['total_payout_fee'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Biaya Operasional</p>
                        <p class="text-red-400 text-lg sm:text-xl font-bold">Rp <?php echo e(number_format($report['summary']['total_operational_cost'] ?? 0, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Breakdown (if available) -->
            <?php if(isset($report['daily_breakdown']) || isset($report['monthly_breakdown'])): ?>
                <div class="glass p-3 sm:p-4 lg:p-6 rounded-xl border border-white/5">
                    <h2 class="text-base sm:text-lg font-semibold text-white mb-3 sm:mb-4">
                        <?php if(isset($report['daily_breakdown'])): ?>
                            Breakdown Harian
                        <?php elseif(isset($report['monthly_breakdown'])): ?>
                            Breakdown Bulanan
                        <?php endif; ?>
                    </h2>
                    <div class="overflow-x-auto -mx-3 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full text-xs sm:text-sm">
                                <thead>
                                    <tr class="border-b border-white/10">
                                        <th class="text-left py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Periode</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Transaksi</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Total Nilai</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Fee Platform</th>
                                        <th class="text-right py-2 sm:py-3 px-2 sm:px-4 text-white/70 font-medium whitespace-nowrap">Biaya QRIS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($report['daily_breakdown'])): ?>
                                        <?php $__currentLoopData = $report['daily_breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $daily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="border-b border-white/5 hover:bg-white/5">
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white whitespace-nowrap"><?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?></td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap"><?php echo e(number_format($daily['transactions']['count'], 0, ',', '.')); ?></td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">Rp <?php echo e(number_format($daily['transactions']['total_amount'], 0, ',', '.')); ?></td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-green-400 text-right whitespace-nowrap">Rp <?php echo e(number_format($daily['transactions']['platform_fee'], 0, ',', '.')); ?></td>
                                                <td class="py-2 sm:py-3 px-2 sm:px-4 text-red-400 text-right whitespace-nowrap">Rp <?php echo e(number_format($daily['transactions']['qris_fee'], 0, ',', '.')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php elseif(isset($report['monthly_breakdown'])): ?>
                                        <?php $__currentLoopData = $report['monthly_breakdown']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $monthly): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($monthly['transactions']['count'] > 0): ?>
                                                <tr class="border-b border-white/5 hover:bg-white/5">
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white whitespace-nowrap"><?php echo e(\Carbon\Carbon::create($monthly['period']['year'], $monthly['period']['month'], 1)->format('M Y')); ?></td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap"><?php echo e(number_format($monthly['transactions']['count'], 0, ',', '.')); ?></td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-white text-right whitespace-nowrap">Rp <?php echo e(number_format($monthly['transactions']['total_amount'], 0, ',', '.')); ?></td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-green-400 text-right whitespace-nowrap">Rp <?php echo e(number_format($monthly['transactions']['platform_fee'], 0, ',', '.')); ?></td>
                                                    <td class="py-2 sm:py-3 px-2 sm:px-4 text-red-400 text-right whitespace-nowrap">Rp <?php echo e(number_format($monthly['transactions']['qris_fee'], 0, ',', '.')); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="glass p-6 sm:p-8 rounded-xl border border-white/5 text-center">
                <p class="text-white/60 text-sm sm:text-base">Tidak ada data untuk periode yang dipilih</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/admin/financial-report/index.blade.php ENDPATH**/ ?>