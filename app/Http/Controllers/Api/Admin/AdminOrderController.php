<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends BaseApiController
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get all orders (admin view)
     * 
     * GET /api/v1/admin/orders
     * 
     * Query params:
     * - status: filter by status
     * - type: filter by type (product|service)
     * - q: search by order_number, user name, product/service title
     * - sort: latest|oldest
     * - page, per_page
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product', 'service', 'payment', 'rating']);

        // Search across multiple fields
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($productQuery) use ($search) {
                      $productQuery->where('title', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('service', function ($serviceQuery) use ($search) {
                      $serviceQuery->where('title', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Paginate
        $orders = $this->paginate($query, $request);

        return $this->successCollection(
            OrderResource::collection($orders)
        );
    }

    /**
     * Get single order details (admin view)
     * 
     * GET /api/v1/admin/orders/{order_uuid}
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'product.user',
            'service.user',
            'payment',
            'rating',
            'messages',
            'progressUpdates',
            'escrow',
            'sellerEarning'
        ]);

        return $this->success(
            new OrderResource($order)
        );
    }

    /**
     * Update order status (admin action)
     * 
     * PATCH /api/v1/admin/orders/{order_uuid}/status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,processing,completed,cancelled,refunded,disputed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            DB::beginTransaction();

            $this->orderService->updateStatus(
                $order,
                $validated['status'],
                $validated['notes'] ?? 'Status updated by admin',
                'admin'
            );

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Order status updated successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'STATUS_UPDATE_ERROR',
                400
            );
        }
    }

    /**
     * Resolve dispute (admin action)
     * 
     * POST /api/v1/admin/orders/{order_uuid}/resolve-dispute
     */
    public function resolveDispute(Request $request, Order $order)
    {
        $validated = $request->validate([
            'resolution' => ['required', 'in:refund,complete,partial_refund'],
            'notes' => ['required', 'string', 'max:1000'],
            'refund_amount' => ['required_if:resolution,partial_refund', 'numeric', 'min:0'],
        ]);

        if (!$order->is_disputed) {
            return $this->error('This order is not in dispute', [], 'NOT_DISPUTED', 400);
        }

        try {
            DB::beginTransaction();

            // Handle resolution
            switch ($validated['resolution']) {
                case 'refund':
                    // Full refund
                    $this->orderService->updateStatus($order, 'refunded', $validated['notes'], 'admin');
                    // TODO: Process refund to buyer
                    break;

                case 'complete':
                    // Favor seller - complete order
                    $this->orderService->updateStatus($order, 'completed', $validated['notes'], 'admin');
                    // TODO: Release escrow to seller
                    break;

                case 'partial_refund':
                    // Partial refund to buyer, rest to seller
                    // TODO: Implement partial refund logic
                    $this->orderService->updateStatus($order, 'completed', $validated['notes'], 'admin');
                    break;
            }

            // Mark dispute as resolved
            $order->update(['is_disputed' => false]);

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Dispute resolved successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'DISPUTE_RESOLUTION_ERROR',
                400
            );
        }
    }

    /**
     * Get orders statistics
     * 
     * GET /api/v1/admin/orders/statistics
     */
    public function statistics()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'paid_orders' => Order::where('status', 'paid')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'disputed_orders' => Order::where('is_disputed', true)->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'average_order_value' => Order::where('status', 'completed')->avg('total') ?? 0,
            'product_orders' => Order::where('type', 'product')->count(),
            'service_orders' => Order::where('type', 'service')->count(),
        ];

        return $this->success($stats);
    }
}

