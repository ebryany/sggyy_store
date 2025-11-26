<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends BaseApiController
{
    protected AdminDashboardService $dashboardService;

    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get admin dashboard overview
     * 
     * GET /api/v1/admin/dashboard
     * 
     * Returns:
     * - Statistics (users, orders, revenue, etc.)
     * - Alerts (pending actions)
     * - Charts (revenue, order trends)
     * - Recent activities
     */
    public function index(Request $request)
    {
        try {
            $data = [
                'stats' => $this->dashboardService->getStats(),
                'alerts' => $this->dashboardService->getAlerts(),
            ];

            // Include charts if requested
            if ($request->boolean('include_charts', true)) {
                $data['charts'] = [
                    'revenue' => $this->dashboardService->getRevenueChart(6),
                    'orders' => $this->dashboardService->getOrderTrend(30),
                ];
            }

            // Include recent activities if requested
            if ($request->boolean('include_activities', true)) {
                $data['recent_activities'] = $this->dashboardService->getRecentActivities(10);
            }

            // Include pending actions if requested
            if ($request->boolean('include_pending', false)) {
                $data['pending_actions'] = $this->dashboardService->getPendingActions(5);
            }

            // Include top performers if requested
            if ($request->boolean('include_top_performers', false)) {
                $data['top_performers'] = $this->dashboardService->getTopPerformers(5);
            }

            return $this->success($data);

        } catch (\Exception $e) {
            return $this->error(
                'Failed to load dashboard data',
                [],
                'DASHBOARD_ERROR',
                500
            );
        }
    }

    /**
     * Get revenue chart data
     * 
     * GET /api/v1/admin/dashboard/revenue-chart
     */
    public function revenueChart(Request $request)
    {
        $months = $request->integer('months', 6);
        $months = min(max($months, 1), 12); // Between 1 and 12

        $data = $this->dashboardService->getRevenueChart($months);

        return $this->success([
            'chart_data' => $data,
            'period' => "{$months} months",
        ]);
    }

    /**
     * Get order trend data
     * 
     * GET /api/v1/admin/dashboard/order-trend
     */
    public function orderTrend(Request $request)
    {
        $days = $request->integer('days', 30);
        $days = min(max($days, 7), 90); // Between 7 and 90

        $data = $this->dashboardService->getOrderTrend($days);

        return $this->success([
            'chart_data' => $data,
            'period' => "{$days} days",
        ]);
    }

    /**
     * Get user growth data
     * 
     * GET /api/v1/admin/dashboard/user-growth
     */
    public function userGrowth(Request $request)
    {
        $weeks = $request->integer('weeks', 12);
        $weeks = min(max($weeks, 4), 52); // Between 4 and 52

        $data = $this->dashboardService->getUserGrowth($weeks);

        return $this->success([
            'chart_data' => $data,
            'period' => "{$weeks} weeks",
        ]);
    }

    /**
     * Clear dashboard cache
     * 
     * POST /api/v1/admin/dashboard/clear-cache
     */
    public function clearCache()
    {
        try {
            $this->dashboardService->clearCache();

            return $this->success(null, 'Dashboard cache cleared successfully');

        } catch (\Exception $e) {
            return $this->error(
                'Failed to clear cache',
                [],
                'CACHE_ERROR',
                500
            );
        }
    }
}

