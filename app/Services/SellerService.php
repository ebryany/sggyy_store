<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\SellerEarning;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SellerService
{
    public function __construct(
        private WalletService $walletService,
        private SettingsService $settingsService
    ) {}

    // Seller Statistics
    public function getSellerStats(User $seller): array
    {
        $products = Product::where('user_id', $seller->id);
        $services = Service::where('user_id', $seller->id);
        
        // Get seller orders (orders for products/services owned by seller)
        $sellerOrders = Order::where(function ($query) use ($seller) {
            $query->whereHas('product', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            })->orWhereHas('service', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            });
        });

        $totalRevenue = SellerEarning::where('seller_id', $seller->id)
            ->where('status', 'available')
            ->sum('amount');

        $monthlyRevenue = SellerEarning::where('seller_id', $seller->id)
            ->where('status', 'available')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return [
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'total_products' => $products->count(),
            'total_services' => $services->count(),
            'total_orders' => $sellerOrders->count(),
            'completed_orders' => $sellerOrders->where('status', 'completed')->count(),
            'pending_orders' => $sellerOrders->where('status', 'pending')->count(),
            'processing_orders' => $sellerOrders->where('status', 'processing')->count(),
            'average_rating' => $this->getAverageRating($seller),
            'total_ratings' => $this->getTotalRatings($seller),
        ];
    }

    // Revenue Analytics
    public function getRevenueChart(User $seller, int $months = 6): array
    {
        $data = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = SellerEarning::where('seller_id', $seller->id)
                ->where('status', 'available')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('amount');
            
            $data[] = [
                'month' => $date->format('M Y'),
                'revenue' => $revenue,
            ];
        }
        return $data;
    }

    // Order Analytics
    public function getOrderTrend(User $seller, int $days = 30): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $orders = Order::where(function ($query) use ($seller) {
                $query->whereHas('product', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                })->orWhereHas('service', function ($q) use ($seller) {
                    $q->where('user_id', $seller->id);
                });
            })->whereDate('created_at', $date->toDateString())->count();
            
            $data[] = [
                'date' => $date->format('d M'),
                'orders' => $orders,
            ];
        }
        return $data;
    }
    
    /**
     * Get order queue (optimized: single query for all statuses)
     */
    public function getOrderQueue(User $seller): array
    {
        // Single query untuk semua order seller
        $allOrders = Order::where(function ($query) use ($seller) {
            $query->whereHas('product', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            })->orWhereHas('service', function ($q) use ($seller) {
                $q->where('user_id', $seller->id);
            });
        })
        ->with(['product', 'service', 'user'])
        ->whereIn('status', ['pending', 'paid', 'processing', 'completed'])
        ->orderByRaw("CASE priority 
            WHEN 'urgent' THEN 1 
            WHEN 'high' THEN 2 
            WHEN 'normal' THEN 3 
            WHEN 'low' THEN 4 
            ELSE 5 END ASC")
        ->orderBy('deadline_at', 'asc')
        ->get();
        
        // Group by status di application layer (lebih efisien)
        // Sort by priority within each group
        $priorityOrder = ['urgent' => 1, 'high' => 2, 'normal' => 3, 'low' => 4];
        
        $sortByPriority = function($order) use ($priorityOrder) {
            return $priorityOrder[$order->priority ?? 'normal'] ?? 5;
        };
        
        return [
            'pending' => $allOrders->whereIn('status', ['pending', 'paid'])
                ->sortBy($sortByPriority)
                ->take(5)
                ->values(),
            'processing' => $allOrders->where('status', 'processing')
                ->sortBy($sortByPriority)
                ->take(5)
                ->values(),
            'completed_today' => $allOrders->where('status', 'completed')
                ->filter(function($order) {
                    return $order->completed_at && $order->completed_at->isToday();
                })
                ->sortBy($sortByPriority)
                ->take(5)
                ->values(),
        ];
    }

    // Product Analytics
    public function getProductAnalytics(Product $product): array
    {
        $orders = Order::where('product_id', $product->id)->where('status', 'completed');
        
        return [
            'views' => 0, // TODO: Implement view tracking
            'purchases' => $orders->count(),
            'revenue' => $orders->sum('total'),
            'rating' => $product->averageRating(),
            'rating_count' => $product->ratings()->count(),
            'conversion_rate' => 0, // TODO: Calculate from views
        ];
    }

    // Service Analytics
    public function getServiceAnalytics(Service $service): array
    {
        $orders = Order::where('service_id', $service->id)->where('status', 'completed');
        
        return [
            'views' => 0, // TODO: Implement view tracking
            'purchases' => $orders->count(),
            'revenue' => $orders->sum('total'),
            'rating' => $service->averageRating(),
            'rating_count' => $service->ratings()->count(),
            'completed_count' => $service->completed_count,
        ];
    }

    // Earnings Management
    public function createEarning(Order $order, ?float $platformFeePercent = null): SellerEarning
    {
        return DB::transaction(function () use ($order, $platformFeePercent) {
            $sellerId = $order->type === 'product' 
                ? $order->product->user_id 
                : $order->service->user_id;

            // Get commission from settings if not provided
            if ($platformFeePercent === null) {
                $platformFeePercent = $this->settingsService->getCommissionForType($order->type);
            }

            $platformFee = ($order->total * $platformFeePercent) / 100;
            $earnings = $order->total - $platformFee;

            return SellerEarning::create([
                'seller_id' => $sellerId,
                'order_id' => $order->id,
                'amount' => $earnings,
                'platform_fee' => $platformFee,
                'status' => 'pending',
            ]);
        });
    }

    public function markEarningAvailable(SellerEarning $earning): SellerEarning
    {
        return DB::transaction(function () use ($earning) {
            $earning->update([
                'status' => 'available',
                'available_at' => now(),
            ]);
            return $earning->fresh();
        });
    }

    // Withdrawable Balance
    public function getWithdrawableBalance(User $seller): float
    {
        return (float) SellerEarning::where('seller_id', $seller->id)
            ->where('status', 'available')
            ->sum('amount');
    }

    // Helper methods
    private function getAverageRating(User $seller): float
    {
        $productRatings = Product::where('user_id', $seller->id)
            ->with('ratings')
            ->get()
            ->flatMap->ratings;
        
        $serviceRatings = Service::where('user_id', $seller->id)
            ->with('ratings')
            ->get()
            ->flatMap->ratings;
        
        $allRatings = $productRatings->merge($serviceRatings);
        
        return $allRatings->avg('rating') ?? 0;
    }

    private function getTotalRatings(User $seller): int
    {
        $productRatings = Product::where('user_id', $seller->id)
            ->withCount('ratings')
            ->get()
            ->sum('ratings_count');
        
        $serviceRatings = Service::where('user_id', $seller->id)
            ->withCount('ratings')
            ->get()
            ->sum('ratings_count');
        
        return $productRatings + $serviceRatings;
    }
}

