<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Services\SellerService;
use App\Services\WalletService;
use App\Services\TimelineService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerDashboardController extends Controller
{
    public function __construct(
        private SellerService $sellerService,
        private WalletService $walletService,
        private TimelineService $timelineService
    ) {
        $this->middleware(['auth', 'seller']);
    }

    public function index(Request $request): View
    {
        $seller = auth()->user();
        
        // Get seller stats
        $stats = $this->sellerService->getSellerStats($seller);
        
        // Get revenue chart data
        $revenueChart = $this->sellerService->getRevenueChart($seller, 6);
        
        // Get order trend
        $orderTrend = $this->sellerService->getOrderTrend($seller, 30);
        
        // Get recent orders
        $recentOrders = Order::where(function ($query) use ($seller) {
            $query->whereHas('product', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            })->orWhereHas('service', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            });
        })->with(['product', 'service', 'payment', 'user'])
          ->latest()
          ->limit(10)
          ->get();
        
        // Get recent transactions (earnings)
        $recentTransactions = $seller->sellerEarnings()
            ->with(['order.product', 'order.service'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Get withdrawable balance
        $withdrawableBalance = $this->sellerService->getWithdrawableBalance($seller);
        
        // Order Queue (Kanban style) - Optimized: single query
        $orderQueue = $this->sellerService->getOrderQueue($seller);
        $pendingOrders = $orderQueue['pending'];
        $processingOrders = $orderQueue['processing'];
        $completedOrdersToday = $orderQueue['completed_today'];
        
        return view('seller.dashboard.index', compact(
            'stats',
            'revenueChart',
            'orderTrend',
            'recentOrders',
            'recentTransactions',
            'withdrawableBalance',
            'pendingOrders',
            'processingOrders',
            'completedOrdersToday'
        ));
    }

    public function analytics(Request $request): View
    {
        $seller = auth()->user();
        
        // Get revenue chart data
        $revenueChart = $this->sellerService->getRevenueChart($seller, 6);
        
        // Get order trend
        $orderTrend = $this->sellerService->getOrderTrend($seller, 30);
        
        return view('seller.analytics.index', compact(
            'revenueChart',
            'orderTrend'
        ));
    }

    public function services(Request $request): View
    {
        $seller = auth()->user();
        
        $query = Service::with(['user', 'ratings'])
            ->where('user_id', $seller->id); // Only seller's own services

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search);
            $search = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $search);
            $search = mb_substr($search, 0, 100);
            
            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $status = trim($request->status);
            $validStatuses = ['active', 'inactive', 'draft'];
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            }
        } else {
            // Default: show all statuses for seller
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $minPrice = filter_var($request->min_price, FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false && $minPrice >= 0) {
                $query->where('price', '>=', $minPrice);
            }
        }

        if ($request->filled('max_price')) {
            $maxPrice = filter_var($request->max_price, FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false && $maxPrice >= 0) {
                $query->where('price', '<=', $maxPrice);
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'rating', 'popular', 'completed'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')
                      ->orderBy('ratings_avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('completed_count', 'desc');
                break;
            case 'completed':
                $query->orderBy('completed_count', 'desc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 12;
        }

        $services = $query->paginate((int)$perPage)->withQueryString();

        return view('seller.services.index', compact('services'));
    }

    /**
     * Display seller's own products list
     * 🔒 SECURITY: Only shows authenticated seller's products
     */
    public function products(Request $request): View
    {
        $seller = auth()->user();
        
        $query = Product::with(['user', 'ratings'])
            ->where('user_id', $seller->id); // Only seller's own products

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search);
            $search = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $search);
            $search = mb_substr($search, 0, 100);
            
            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // Status filter
        if ($request->filled('status')) {
            $status = trim($request->status);
            $validStatuses = ['active', 'inactive', 'draft'];
            if (in_array($status, $validStatuses)) {
                if ($status === 'active') {
                    $query->where('is_active', true)->where('is_draft', false);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($status === 'draft') {
                    $query->where('is_draft', true);
                }
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $category = trim($request->category);
            $category = strip_tags($category);
            $validCategories = Product::where('user_id', $seller->id)
                ->distinct()
                ->pluck('category')
                ->toArray();
            if (in_array($category, $validCategories)) {
                $query->where('category', $category);
            }
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $minPrice = filter_var($request->min_price, FILTER_VALIDATE_FLOAT);
            if ($minPrice !== false && $minPrice >= 0) {
                $query->where('price', '>=', $minPrice);
            }
        }

        if ($request->filled('max_price')) {
            $maxPrice = filter_var($request->max_price, FILTER_VALIDATE_FLOAT);
            if ($maxPrice !== false && $maxPrice >= 0) {
                $query->where('price', '<=', $maxPrice);
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'rating', 'popular', 'sold'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('ratings', 'rating')
                      ->orderBy('ratings_avg_rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'sold':
                $query->orderBy('sold_count', 'desc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 12);
        $validPerPage = [12, 24, 48, 96];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 12;
        }

        $products = $query->paginate((int)$perPage)->withQueryString();

        return view('seller.products.index', compact('products'));
    }

    /**
     * Display seller's orders list (with seller layout)
     * 🔒 SECURITY: Only shows orders for seller's products/services
     */
    public function orders(Request $request): View
    {
        $seller = auth()->user();
        
        $query = Order::with(['product', 'service', 'payment', 'user'])
            ->where(function ($query) use ($seller) {
                // Only seller's orders
                $query->whereHas('product', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                })->orWhereHas('service', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                });
            });

        // Status filter
        if ($request->filled('status')) {
            $validStatuses = ['pending', 'paid', 'processing', 'completed', 'cancelled', 'needs_revision'];
            $status = $request->status;
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            }
        }

        // Type filter (product or service)
        if ($request->filled('type')) {
            $type = $request->type;
            if (in_array($type, ['product', 'service'])) {
                if ($type === 'product') {
                    $query->whereNotNull('product_id');
                } else {
                    $query->whereNotNull('service_id');
                }
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $dateFrom = $request->date_from;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->date_to;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
                $query->whereDate('created_at', '<=', $dateTo);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $search = strip_tags($search);
            $search = mb_substr($search, 0, 100);
            
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhereHas('product', function ($pq) use ($search) {
                          $pq->where('title', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('service', function ($sq) use ($search) {
                          $sq->where('title', 'like', '%' . $search . '%');
                      });
                });
            }
        }

        // Sorting
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc', 'status'];
        $sort = $request->get('sort', 'newest');
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }

        switch ($sort) {
            case 'newest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'status':
                $query->orderBy('status', 'asc');
                break;
        }

        // Per page
        $perPage = $request->get('per_page', 15);
        $validPerPage = [10, 15, 20, 30, 50];
        if (!in_array((int)$perPage, $validPerPage)) {
            $perPage = 15;
        }

        $orders = $query->paginate((int)$perPage)->withQueryString();

        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display seller's order detail (with seller layout)
     * 🔒 SECURITY: Only shows order if seller owns the product/service
     */
    public function orderShow(Order $order): View
    {
        $seller = auth()->user();
        
        // Verify seller owns this order's product/service
        $ownsOrder = ($order->product && $order->product->user_id === $seller->id) ||
                     ($order->service && $order->service->user_id === $seller->id);
        
        if (!$ownsOrder && !$seller->isAdmin()) {
            abort(403, 'Unauthorized access to this order');
        }

        $order->load(['product', 'service', 'payment.verifier', 'rating', 'history.creator', 'user', 'escrow']);
        
        // Real-time auto-release check: If order is completed and hold period expired, auto-release immediately
        if ($order->status === 'completed' && $order->escrow && $order->escrow->isHolding() && $order->escrow->hold_until && $order->escrow->hold_until <= now()) {
            try {
                $escrowService = app(\App\Services\EscrowService::class);
                $escrowService->autoRelease($order->escrow);
                $order->refresh(); // Refresh to get updated escrow status
                
                \Illuminate\Support\Facades\Log::info('Escrow auto-released in real-time on seller page load (hold period expired)', [
                    'order_id' => $order->id,
                    'escrow_id' => $order->escrow->id,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to auto-release escrow in real-time on seller page load', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                // Don't fail, cron will handle it later
            }
        }
        
        // Timeline
        $timeline = $this->timelineService->getOrderTimeline($order);
        
        // Merge with order history
        if ($order->history->count() > 0) {
            foreach ($order->history as $history) {
                $timeline[] = [
                    'time' => $history->created_at->format('d M Y, H:i'),
                    'label' => 'Status: ' . ucfirst($history->status_to),
                    'status' => $history->status_to === 'completed' ? 'completed' : ($history->status_to === 'cancelled' ? 'cancelled' : 'processing'),
                    'icon' => $history->status_to === 'completed' ? '✅' : ($history->status_to === 'cancelled' ? '❌' : '⚙️'),
                    'description' => $history->notes ?? 'Status order diperbarui',
                ];
            }
        }

        // Sort timeline by time
        usort($timeline, function ($a, $b) {
            return strtotime($a['time']) - strtotime($b['time']);
        });

        $isSeller = true; // Always true in seller context

        return view('seller.orders.show', compact('order', 'timeline', 'isSeller'));
    }

    /**
     * Display seller's wallet (with seller layout)
     * 🔒 SECURITY: Only shows authenticated seller's wallet
     */
    public function wallet(Request $request): View
    {
        $seller = auth()->user();
        $balance = $this->walletService->getBalance($seller);
        
        // Validate type parameter
        $validTypes = ['top_up', 'deduction', 'refund'];
        $type = $request->get('type');
        if ($type && !in_array($type, $validTypes)) {
            $type = null;
        }
        
        // Validate status parameter
        $validStatuses = ['pending', 'completed', 'rejected'];
        $status = $request->get('status');
        if ($status && !in_array($status, $validStatuses)) {
            $status = null;
        }
        
        $transactions = $this->walletService->getTransactionHistory($seller, $type, $status);

        return view('seller.wallet.index', compact('balance', 'transactions', 'type', 'status'));
    }
}
