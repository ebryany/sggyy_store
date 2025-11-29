<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Escrow;
use App\Models\SellerWithdrawal;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Financial Report Service
 * 
 * Service untuk menghitung dan menghasilkan laporan keuangan platform
 */
class FinancialReportService
{
    // Konfigurasi Biaya
    private const PLATFORM_FEE_FIXED = 4000; // Rp 4.000 per transaksi
    private const QRIS_FEE_PERCENT = 5; // 5% dari total transaksi
    private const PAYOUT_FEE_FIXED = 2500; // Rp 2.500
    private const PAYOUT_FEE_PERCENT = 1; // 1% dari jumlah payout

    /**
     * Hitung biaya QRIS
     * 
     * @param float $amount Total transaksi
     * @return float Biaya QRIS
     */
    public function calculateQrisFee(float $amount): float
    {
        return $amount * (self::QRIS_FEE_PERCENT / 100);
    }

    /**
     * Hitung fee platform
     * 
     * @return float Fee platform (fixed)
     */
    public function calculatePlatformFee(): float
    {
        return self::PLATFORM_FEE_FIXED;
    }

    /**
     * Hitung seller earning
     * 
     * @param float $totalAmount Total transaksi
     * @return float Seller earning
     */
    public function calculateSellerEarning(float $totalAmount): float
    {
        return $totalAmount - $this->calculatePlatformFee();
    }

    /**
     * Hitung biaya payout
     * 
     * @param float $payoutAmount Jumlah payout
     * @return float Biaya payout
     */
    public function calculatePayoutFee(float $payoutAmount): float
    {
        return self::PAYOUT_FEE_FIXED + ($payoutAmount * (self::PAYOUT_FEE_PERCENT / 100));
    }

    /**
     * Hitung dana yang diterima seller setelah biaya payout
     * 
     * @param float $payoutAmount Jumlah payout
     * @return float Dana yang diterima seller
     */
    public function calculateSellerReceivedAfterPayout(float $payoutAmount): float
    {
        return $payoutAmount - $this->calculatePayoutFee($payoutAmount);
    }

    /**
     * Hitung dana masuk platform setelah biaya QRIS
     * 
     * @param float $totalAmount Total transaksi
     * @return float Dana masuk platform
     */
    public function calculatePlatformRevenue(float $totalAmount): float
    {
        return $totalAmount - $this->calculateQrisFee($totalAmount);
    }

    /**
     * Generate laporan keuangan harian
     * 
     * @param Carbon|null $date Tanggal (default: hari ini)
     * @return array
     */
    public function getDailyReport(?Carbon $date = null): array
    {
        $date = $date ?? Carbon::today();
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        // Get verified payments
        $payments = Payment::where('status', 'verified')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->with(['order'])
            ->get();

        // Get completed withdrawals
        $withdrawals = SellerWithdrawal::where('status', 'completed')
            ->whereBetween('processed_at', [$startDate, $endDate])
            ->get();

        // Calculate totals
        $totalTransactions = $payments->count();
        $totalTransactionAmount = $payments->sum(fn($p) => $p->order->total ?? 0);
        $totalQrisFee = $payments->sum(fn($p) => $this->calculateQrisFee($p->order->total ?? 0));
        $totalPlatformFee = $totalTransactions * self::PLATFORM_FEE_FIXED;
        $totalSellerEarning = $payments->sum(fn($p) => $this->calculateSellerEarning($p->order->total ?? 0));
        $totalPlatformRevenue = $totalTransactionAmount - $totalQrisFee;

        // Withdrawals
        $totalWithdrawals = $withdrawals->count();
        $totalWithdrawalAmount = $withdrawals->sum('amount');
        $totalPayoutFee = $withdrawals->sum(fn($w) => $this->calculatePayoutFee($w->amount));
        $totalSellerReceived = $withdrawals->sum(fn($w) => $this->calculateSellerReceivedAfterPayout($w->amount));

        return [
            'date' => $date->format('Y-m-d'),
            'transactions' => [
                'count' => $totalTransactions,
                'total_amount' => $totalTransactionAmount,
                'qris_fee' => $totalQrisFee,
                'platform_fee' => $totalPlatformFee,
                'seller_earning' => $totalSellerEarning,
                'platform_revenue' => $totalPlatformRevenue,
            ],
            'withdrawals' => [
                'count' => $totalWithdrawals,
                'total_amount' => $totalWithdrawalAmount,
                'payout_fee' => $totalPayoutFee,
                'seller_received' => $totalSellerReceived,
            ],
            'summary' => [
                'platform_net_income' => $totalPlatformFee,
                'total_payment_gateway_fee' => $totalQrisFee,
                'total_payout_fee' => $totalPayoutFee,
                'total_operational_cost' => $totalQrisFee + $totalPayoutFee,
            ],
        ];
    }

    /**
     * Generate laporan keuangan bulanan
     * 
     * @param int|null $year Tahun (default: tahun ini)
     * @param int|null $month Bulan (default: bulan ini)
     * @return array
     */
    public function getMonthlyReport(?int $year = null, ?int $month = null): array
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;

        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Get verified payments
        $payments = Payment::where('status', 'verified')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->with(['order'])
            ->get();

        // Get completed withdrawals
        $withdrawals = SellerWithdrawal::where('status', 'completed')
            ->whereBetween('processed_at', [$startDate, $endDate])
            ->get();

        // Calculate totals
        $totalTransactions = $payments->count();
        $totalTransactionAmount = $payments->sum(fn($p) => $p->order->total ?? 0);
        $totalQrisFee = $payments->sum(fn($p) => $this->calculateQrisFee($p->order->total ?? 0));
        $totalPlatformFee = $totalTransactions * self::PLATFORM_FEE_FIXED;
        $totalSellerEarning = $payments->sum(fn($p) => $this->calculateSellerEarning($p->order->total ?? 0));
        $totalPlatformRevenue = $totalTransactionAmount - $totalQrisFee;

        // Withdrawals
        $totalWithdrawals = $withdrawals->count();
        $totalWithdrawalAmount = $withdrawals->sum('amount');
        $totalPayoutFee = $withdrawals->sum(fn($w) => $this->calculatePayoutFee($w->amount));
        $totalSellerReceived = $withdrawals->sum(fn($w) => $this->calculateSellerReceivedAfterPayout($w->amount));

        // Daily breakdown
        $dailyBreakdown = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dailyBreakdown[$currentDate->format('Y-m-d')] = $this->getDailyReport($currentDate);
            $currentDate->addDay();
        }

        return [
            'period' => [
                'year' => $year,
                'month' => $month,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'transactions' => [
                'count' => $totalTransactions,
                'total_amount' => $totalTransactionAmount,
                'qris_fee' => $totalQrisFee,
                'platform_fee' => $totalPlatformFee,
                'seller_earning' => $totalSellerEarning,
                'platform_revenue' => $totalPlatformRevenue,
            ],
            'withdrawals' => [
                'count' => $totalWithdrawals,
                'total_amount' => $totalWithdrawalAmount,
                'payout_fee' => $totalPayoutFee,
                'seller_received' => $totalSellerReceived,
            ],
            'summary' => [
                'platform_net_income' => $totalPlatformFee,
                'total_payment_gateway_fee' => $totalQrisFee,
                'total_payout_fee' => $totalPayoutFee,
                'total_operational_cost' => $totalQrisFee + $totalPayoutFee,
            ],
            'daily_breakdown' => $dailyBreakdown,
        ];
    }

    /**
     * Generate laporan keuangan tahunan
     * 
     * @param int|null $year Tahun (default: tahun ini)
     * @return array
     */
    public function getYearlyReport(?int $year = null): array
    {
        $year = $year ?? Carbon::now()->year;

        $startDate = Carbon::create($year, 1, 1)->startOfDay();
        $endDate = Carbon::create($year, 12, 31)->endOfDay();

        // Get verified payments
        $payments = Payment::where('status', 'verified')
            ->whereBetween('verified_at', [$startDate, $endDate])
            ->with(['order'])
            ->get();

        // Get completed withdrawals
        $withdrawals = SellerWithdrawal::where('status', 'completed')
            ->whereBetween('processed_at', [$startDate, $endDate])
            ->get();

        // Calculate totals
        $totalTransactions = $payments->count();
        $totalTransactionAmount = $payments->sum(fn($p) => $p->order->total ?? 0);
        $totalQrisFee = $payments->sum(fn($p) => $this->calculateQrisFee($p->order->total ?? 0));
        $totalPlatformFee = $totalTransactions * self::PLATFORM_FEE_FIXED;
        $totalSellerEarning = $payments->sum(fn($p) => $this->calculateSellerEarning($p->order->total ?? 0));
        $totalPlatformRevenue = $totalTransactionAmount - $totalQrisFee;

        // Withdrawals
        $totalWithdrawals = $withdrawals->count();
        $totalWithdrawalAmount = $withdrawals->sum('amount');
        $totalPayoutFee = $withdrawals->sum(fn($w) => $this->calculatePayoutFee($w->amount));
        $totalSellerReceived = $withdrawals->sum(fn($w) => $this->calculateSellerReceivedAfterPayout($w->amount));

        // Monthly breakdown
        $monthlyBreakdown = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyBreakdown[$month] = $this->getMonthlyReport($year, $month);
        }

        return [
            'period' => [
                'year' => $year,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
            ],
            'transactions' => [
                'count' => $totalTransactions,
                'total_amount' => $totalTransactionAmount,
                'qris_fee' => $totalQrisFee,
                'platform_fee' => $totalPlatformFee,
                'seller_earning' => $totalSellerEarning,
                'platform_revenue' => $totalPlatformRevenue,
            ],
            'withdrawals' => [
                'count' => $totalWithdrawals,
                'total_amount' => $totalWithdrawalAmount,
                'payout_fee' => $totalPayoutFee,
                'seller_received' => $totalSellerReceived,
            ],
            'summary' => [
                'platform_net_income' => $totalPlatformFee,
                'total_payment_gateway_fee' => $totalQrisFee,
                'total_payout_fee' => $totalPayoutFee,
                'total_operational_cost' => $totalQrisFee + $totalPayoutFee,
            ],
            'monthly_breakdown' => $monthlyBreakdown,
        ];
    }

    /**
     * Get configuration values
     * 
     * @return array
     */
    public function getConfiguration(): array
    {
        return [
            'platform_fee_fixed' => self::PLATFORM_FEE_FIXED,
            'qris_fee_percent' => self::QRIS_FEE_PERCENT,
            'payout_fee_fixed' => self::PAYOUT_FEE_FIXED,
            'payout_fee_percent' => self::PAYOUT_FEE_PERCENT,
        ];
    }
}

