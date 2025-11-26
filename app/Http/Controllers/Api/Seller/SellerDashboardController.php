<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\SellerEarning;
use Illuminate\Http\Request;

class SellerDashboardController extends BaseApiController
{
    /**
     * Get seller dashboard data
     * 
     * GET /api/v1/seller/dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get statistics
        $totalProducts = Product::where('user_id', $user->id)->count();
        $activeProducts = Product::where('user_id', $user->id)->where('is_active', true)->count();
        
        $totalServices = Service::where('user_id', $user->id)->count();
        $activeServices = Service::where('user_id', $user->id)->where('status', 'active')->count();

        $totalOrders = Order::where(function ($q) use ($user) {
            $q->whereHas('product', fn($q) => $q->where('user_id', $user->id))
              ->orWhereHas('service', fn($q) => $q->where('user_id', $user->id));
        })->count();

        $pendingOrders = Order::where(function ($q) use ($user) {
            $q->whereHas('product', fn($q) => $q->where('user_id', $user->id))
              ->orWhereHas('service', fn($q) => $q->where('user_id', $user->id));
        })->whereIn('status', ['pending', 'paid', 'processing'])->count();

        $completedOrders = Order::where(function ($q) use ($user) {
            $q->whereHas('product', fn($q) => $q->where('user_id', $user->id))
              ->orWhereHas('service', fn($q) => $q->where('user_id', $user->id));
        })->where('status', 'completed')->count();

        $totalEarnings = SellerEarning::where('seller_id', $user->id)->sum('amount');
        $availableEarnings = SellerEarning::where('seller_id', $user->id)
            ->where('status', 'available')
            ->sum('amount');
        $pendingEarnings = SellerEarning::where('seller_id', $user->id)
            ->where('status', 'pending')
            ->sum('amount');

        return $this->success([
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
            ],
            'services' => [
                'total' => $totalServices,
                'active' => $activeServices,
            ],
            'orders' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'completed' => $completedOrders,
            ],
            'earnings' => [
                'total' => (float) $totalEarnings,
                'available' => (float) $availableEarnings,
                'pending' => (float) $pendingEarnings,
            ],
            'balance' => (float) $user->balance,
        ]);
    }
}

