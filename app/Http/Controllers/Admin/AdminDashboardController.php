<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        private AdminDashboardService $dashboardService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request): View
    {
        // Use service layer for optimized queries with caching
        $stats = $this->dashboardService->getStats();
        $alerts = $this->dashboardService->getAlerts();
        $revenueChart = $this->dashboardService->getRevenueChart(6);
        $orderTrend = $this->dashboardService->getOrderTrend(30);
        $userGrowth = $this->dashboardService->getUserGrowth(12);
        $recentActivities = $this->dashboardService->getRecentActivities(10);
        
        // Get pending actions (detailed)
        $pendingActions = $this->dashboardService->getPendingActions(5);
        $pendingPayments = $pendingActions['pending_payments'];
        $pendingWithdrawals = $pendingActions['pending_withdrawals'];
        $pendingTopups = $pendingActions['pending_topups'];
        $pendingVerifications = $pendingActions['pending_verifications'];
        $overdueOrders = $pendingActions['overdue_orders'];
        
        // Get top performers
        $topPerformers = $this->dashboardService->getTopPerformers(5);
        $topSellers = $topPerformers['top_sellers'];
        $topProducts = $topPerformers['top_products'];
        
        return view('admin.dashboard.index', compact(
            'stats',
            'alerts',
            'revenueChart',
            'orderTrend',
            'userGrowth',
            'recentActivities',
            'pendingPayments',
            'pendingWithdrawals',
            'pendingTopups',
            'pendingVerifications',
            'overdueOrders',
            'topSellers',
            'topProducts'
        ));
    }
}
