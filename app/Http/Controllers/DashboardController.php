<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\ProfileService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private ProfileService $profileService,
        private NotificationService $notificationService,
        private WalletService $walletService,
        private OrderService $orderService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = auth()->user();

        // User stats
        $totalOrders = Order::where('user_id', $user->id)->count();
        $walletBalance = $this->walletService->getBalance($user);
        $activeProducts = Product::where('user_id', $user->id)
            ->where('is_active', true)
            ->count();
        $activeServices = Service::where('user_id', $user->id)
            ->where('status', 'active')
            ->count();

        // Order status counts (optimized: single query with groupBy)
        $statusCounts = Order::where('user_id', $user->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        $orderStatusCounts = [
            'pending' => $statusCounts['pending'] ?? 0,
            'processing' => $statusCounts['processing'] ?? 0,
            'completed' => $statusCounts['completed'] ?? 0,
            'cancelled' => $statusCounts['cancelled'] ?? 0,
        ];

        // Recent orders
        $recentOrders = Order::with(['product', 'service', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();
        
        // Auto-complete orders that are stuck (100% progress but not completed)
        // This handles edge cases where auto-complete didn't trigger
        $stuckOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['paid', 'processing'])
            ->where('progress', 100)
            ->get();
        
        foreach ($stuckOrders as $order) {
            try {
                $this->orderService->updateStatus(
                    $order,
                    'completed',
                    'Auto-completed: Progress sudah 100%',
                    'system'
                );
            } catch (\Exception $e) {
                // Log error but continue
                \Illuminate\Support\Facades\Log::error('Failed to auto-complete stuck order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        // In Progress Orders (for Tugas Dalam Progress widget)
        $inProgressOrders = Order::with(['product', 'service'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['paid', 'processing'])
            ->orderBy('deadline_at', 'asc')
            ->limit(5)
            ->get();
        
        // Completed Orders with downloadable files
        $completedOrders = Order::with(['product', 'service'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->limit(10)
            ->get();

        // Last top-up
        $lastTopUp = WalletTransaction::where('user_id', $user->id)
            ->where('type', 'top_up')
            ->latest()
            ->first();

        // Recent notifications
        $recentNotifications = $this->notificationService->getNotifications($user, 5);
        $unreadCount = $this->notificationService->getUnreadCount($user);

        // Profile completion
        $profileCompletion = $this->profileService->getProfileCompletion($user);

        return view('dashboard.index', compact(
            'totalOrders',
            'walletBalance',
            'activeProducts',
            'activeServices',
            'orderStatusCounts',
            'recentOrders',
            'inProgressOrders',
            'completedOrders',
            'lastTopUp',
            'recentNotifications',
            'unreadCount',
            'profileCompletion'
        ));
    }
}