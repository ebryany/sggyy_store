<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\SellerWithdrawal;
use App\Models\SellerVerification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    /**
     * Get optimized overview statistics
     * Uses aggregated queries and caching for performance
     */
    public function getStats(): array
    {
        return Cache::remember('admin.dashboard.stats', 600, function () {
            $today = today();
            $thisMonth = now()->month;
            $thisYear = now()->year;
            
            // User statistics (optimized)
            $totalUsers = User::count();
            $newUsersToday = User::whereDate('created_at', $today)->count();
            $newUsersThisMonth = User::whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->count();
            $activeSellers = User::where('role', 'seller')->count();
            $verifiedSellers = User::where('role', 'seller')
                ->whereNotNull('email_verified_at')
                ->count();
            
            // Product & Service statistics (optimized)
            $totalProducts = Product::count();
            $activeProducts = Product::where('is_active', true)->count();
            $totalServices = Service::count();
            $activeServices = Service::where('status', 'active')->count();
            
            // Order statistics (SQLite-compatible)
            $totalOrders = Order::count();
            $ordersToday = Order::whereDate('created_at', $today)->count();
            $ordersThisMonth = Order::whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $processingOrders = Order::where('status', 'processing')->count();
            $completedOrders = Order::where('status', 'completed')->count();
            
            // Financial statistics (SQLite-compatible)
            $totalRevenue = Order::where('status', 'completed')->sum('total');
            $revenueToday = Order::where('status', 'completed')
                ->whereDate('completed_at', $today)
                ->sum('total');
            $revenueThisMonth = Order::where('status', 'completed')
                ->whereMonth('completed_at', $thisMonth)
                ->whereYear('completed_at', $thisYear)
                ->sum('total');
            $averageOrderValue = Order::where('status', 'completed')->avg('total') ?? 0;
            
            // Product vs Service revenue
            $productRevenue = Order::where('status', 'completed')
                ->where('type', 'product')
                ->sum('total');
            $serviceRevenue = Order::where('status', 'completed')
                ->where('type', 'service')
                ->sum('total');
            
            // Calculate platform commission (10% products, 15% services)
            $platformCommission = ($productRevenue * 0.10) + ($serviceRevenue * 0.15);
            
            return [
                'total_users' => $totalUsers,
                'new_users_today' => $newUsersToday,
                'new_users_this_month' => $newUsersThisMonth,
                'active_sellers' => $activeSellers,
                'verified_sellers' => $verifiedSellers,
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'total_services' => $totalServices,
                'active_services' => $activeServices,
                'total_orders' => $totalOrders,
                'orders_today' => $ordersToday,
                'orders_this_month' => $ordersThisMonth,
                'pending_orders' => $pendingOrders,
                'processing_orders' => $processingOrders,
                'completed_orders' => $completedOrders,
                'total_revenue' => $totalRevenue,
                'revenue_today' => $revenueToday,
                'revenue_this_month' => $revenueThisMonth,
                'average_order_value' => $averageOrderValue,
                'platform_commission' => $platformCommission,
            ];
        });
    }
    
    /**
     * Get alerts and pending actions
     */
    public function getAlerts(): array
    {
        return Cache::remember('admin.dashboard.alerts', 300, function () {
            // Optimized: Single query for all alerts using subqueries
            // Use Laravel query builder for database compatibility
            $alerts = [
                // Only count payments that are truly pending (not expired)
                // Payment expiry is stored in orders table, so we need to join
                'pending_payments' => Payment::where('status', 'pending')
                    ->whereHas('order', function($query) {
                        // Only count payments for orders that haven't expired
                        $query->where(function($q) {
                            $q->whereNull('payment_expires_at')
                              ->orWhere('payment_expires_at', '>', now());
                        });
                    })
                    ->count(),
                'pending_topups' => WalletTransaction::where('type', 'top_up')->where('status', 'pending')->count(),
                'pending_withdrawals' => SellerWithdrawal::where('status', 'pending')->count(),
                'pending_verifications' => SellerVerification::where('status', 'pending')->count(),
                'overdue_orders' => Order::whereIn('status', ['paid', 'processing'])
                    ->whereNotNull('deadline_at')
                    ->where('deadline_at', '<', now())
                    ->count(),
                'unread_notifications' => Notification::where('is_read', false)->count(),
            ];
            
            return $alerts;
        });
    }
    
    /**
     * Get revenue chart data (last N months)
     */
    public function getRevenueChart(int $months = 6): array
    {
        return Cache::remember("admin.dashboard.revenue_chart.{$months}", 1800, function () use ($months) {
            $data = [];
            for ($i = $months - 1; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $revenue = Order::where('status', 'completed')
                    ->whereYear('completed_at', $month->year)
                    ->whereMonth('completed_at', $month->month)
                    ->sum('total');
                
                $data[] = [
                    'month' => $month->format('M Y'),
                    'revenue' => $revenue,
                ];
            }
            return $data;
        });
    }
    
    /**
     * Get order trend (last N days)
     */
    public function getOrderTrend(int $days = 30): array
    {
        return Cache::remember("admin.dashboard.order_trend.{$days}", 1800, function () use ($days) {
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $orders = Order::whereDate('created_at', $date)->count();
                
                $data[] = [
                    'date' => $date->format('d M'),
                    'orders' => $orders,
                ];
            }
            return $data;
        });
    }
    
    /**
     * Get user growth (last N weeks)
     */
    public function getUserGrowth(int $weeks = 12): array
    {
        return Cache::remember("admin.dashboard.user_growth.{$weeks}", 1800, function () use ($weeks) {
            $data = [];
            for ($i = $weeks - 1; $i >= 0; $i--) {
                $week = now()->subWeeks($i);
                $users = User::whereBetween('created_at', [
                    $week->startOfWeek()->copy(),
                    $week->endOfWeek()->copy()
                ])->count();
                
                $data[] = [
                    'week' => $week->format('W'),
                    'users' => $users,
                ];
            }
            return $data;
        });
    }
    
    /**
     * Get recent platform activities
     */
    public function getRecentActivities(int $limit = 10): array
    {
        $activities = collect();
        
        // Recent user registrations
        $newUsers = User::latest()->limit(3)->get()->map(function($user) {
            return [
                'type' => 'user_registered',
                'message' => "New user registered: {$user->name}",
                'time' => $user->created_at,
                'icon' => 'user',
                'color' => 'blue',
            ];
        });
        
        // Recent completed orders
        $completedOrders = Order::where('status', 'completed')
            ->with(['user', 'product', 'service'])
            ->latest('completed_at')
            ->limit(3)
            ->get()
            ->map(function($order) {
                $item = $order->type === 'product' 
                    ? $order->product?->title 
                    : $order->service?->title;
                return [
                    'type' => 'order_completed',
                    'message' => "Order #{$order->order_number} completed: {$item}",
                    'time' => $order->completed_at,
                    'icon' => 'check',
                    'color' => 'green',
                ];
            });
        
        // Recent product listings
        $newProducts = Product::latest()->limit(2)->get()->map(function($product) {
            return [
                'type' => 'product_listed',
                'message' => "New product listed: {$product->title}",
                'time' => $product->created_at,
                'icon' => 'package',
                'color' => 'purple',
            ];
        });
        
        // Merge and sort by time
        $activities = $activities->merge($newUsers)
            ->merge($completedOrders)
            ->merge($newProducts)
            ->sortByDesc('time')
            ->take($limit)
            ->values();
        
        return $activities->toArray();
    }
    
    /**
     * Get pending actions (detailed)
     */
    public function getPendingActions(int $limit = 5): array
    {
        return [
            'pending_payments' => Payment::where('status', 'pending')
                ->with(['order.user', 'order.product', 'order.service'])
                ->latest()
                ->limit($limit)
                ->get(),
            'pending_withdrawals' => SellerWithdrawal::where('status', 'pending')
                ->with('seller')
                ->latest()
                ->limit($limit)
                ->get(),
            'pending_topups' => WalletTransaction::where('type', 'top_up')
                ->where('status', 'pending')
                ->with('user')
                ->latest()
                ->limit($limit)
                ->get(),
            'pending_verifications' => SellerVerification::where('status', 'pending')
                ->with('user')
                ->latest()
                ->limit($limit)
                ->get(),
            'overdue_orders' => Order::whereIn('status', ['paid', 'processing'])
                ->whereNotNull('deadline_at')
                ->where('deadline_at', '<', now())
                ->with(['user', 'product', 'service'])
                ->orderBy('deadline_at', 'asc')
                ->limit($limit)
                ->get(),
        ];
    }
    
    /**
     * Get top performers
     */
    public function getTopPerformers(int $limit = 5): array
    {
        // Top Sellers - using subquery for better compatibility
        $topSellers = User::where('role', 'seller')
            ->withCount(['products', 'services'])
            ->select('users.*')
            ->selectRaw('(
                SELECT COALESCE(SUM(orders.total), 0)
                FROM products
                INNER JOIN orders ON products.id = orders.product_id
                WHERE products.user_id = users.id
                AND orders.status = "completed"
            ) as total_sales')
            ->orderByDesc('total_sales')
            ->limit($limit)
            ->get();

        // Top Products - using subquery
        $topProducts = Product::select('products.*')
            ->selectRaw('(
                SELECT COUNT(*)
                FROM orders
                WHERE orders.product_id = products.id
                AND orders.status = "completed"
            ) as orders_count')
            ->selectRaw('(
                SELECT COALESCE(SUM(orders.total), 0)
                FROM orders
                WHERE orders.product_id = products.id
                AND orders.status = "completed"
            ) as revenue')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();

        return [
            'top_sellers' => $topSellers,
            'top_products' => $topProducts,
        ];
    }
    
    /**
     * Clear dashboard cache
     */
    public function clearCache(): void
    {
        Cache::forget('admin.dashboard.stats');
        Cache::forget('admin.dashboard.alerts');
        Cache::forget('admin.dashboard.revenue_chart.6');
        Cache::forget('admin.dashboard.order_trend.30');
        Cache::forget('admin.dashboard.user_growth.12');
        
        // Clear all revenue chart variations
        for ($i = 1; $i <= 12; $i++) {
            Cache::forget("admin.dashboard.revenue_chart.{$i}");
        }
        
        // Clear all order trend variations
        for ($i = 7; $i <= 90; $i += 7) {
            Cache::forget("admin.dashboard.order_trend.{$i}");
        }
    }
}

