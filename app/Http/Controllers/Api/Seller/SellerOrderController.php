<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerOrderController extends BaseApiController
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get seller's orders (orders for their products/services)
     * 
     * GET /api/v1/seller/orders
     * 
     * Query params:
     * - status: filter by status
     * - type: filter by type (product|service)
     * - sort: latest|oldest
     * - page, per_page
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // Query orders for seller's products or services
        $query = Order::where(function ($q) use ($sellerId) {
            $q->whereHas('product', function ($productQuery) use ($sellerId) {
                $productQuery->where('user_id', $sellerId);
            })->orWhereHas('service', function ($serviceQuery) use ($sellerId) {
                $serviceQuery->where('user_id', $sellerId);
            });
        })->with(['user', 'product', 'service', 'payment', 'rating']);

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
     * Get single order details (seller's order)
     * 
     * GET /api/v1/seller/orders/{order_uuid}
     */
    public function show(Order $order)
    {
        $sellerId = auth()->id();

        // Check if this order belongs to seller's product/service
        $belongsToSeller = ($order->product && $order->product->user_id === $sellerId) ||
                           ($order->service && $order->service->user_id === $sellerId);

        if (!$belongsToSeller) {
            return $this->forbidden('You do not have access to this order');
        }

        $order->load([
            'user',
            'product',
            'service',
            'payment',
            'rating',
            'messages',
            'progressUpdates',
            'escrow'
        ]);

        return $this->success(
            new OrderResource($order)
        );
    }

    /**
     * Accept order (for services)
     * 
     * POST /api/v1/seller/orders/{order_uuid}/accept
     */
    public function accept(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        // Check authorization
        $belongsToSeller = ($order->product && $order->product->user_id === $sellerId) ||
                           ($order->service && $order->service->user_id === $sellerId);

        if (!$belongsToSeller) {
            return $this->forbidden('You do not have access to this order');
        }

        // Only service orders can be accepted
        if ($order->type !== 'service') {
            return $this->error('Only service orders can be accepted', [], 'INVALID_ORDER_TYPE', 400);
        }

        // Check if order is in correct status
        if ($order->status !== 'paid') {
            return $this->error('Order must be in paid status to be accepted', [], 'INVALID_STATUS', 400);
        }

        try {
            DB::beginTransaction();

            // Update order status to processing
            $this->orderService->updateStatus($order, 'processing', 'Order accepted by seller', 'seller');

            // Set deadline if duration_hours is provided
            if ($order->service && $order->service->duration_hours) {
                $order->update([
                    'accepted_at' => now(),
                    'deadline_at' => now()->addHours($order->service->duration_hours),
                ]);
            }

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Order accepted successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'ACCEPT_ERROR',
                400
            );
        }
    }

    /**
     * Update order progress (for services)
     * 
     * PATCH /api/v1/seller/orders/{order_uuid}/progress
     */
    public function updateProgress(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        // Check authorization
        $seller = $order->service?->user;
        if (!$seller || $seller->id !== $sellerId) {
            return $this->forbidden('Only seller can update progress');
        }

        $validated = $request->validate([
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $this->orderService->updateProgress(
                $order,
                $validated['progress'],
                $validated['note'] ?? null
            );

            return $this->success(
                new OrderResource($order->fresh()),
                'Progress updated successfully'
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'PROGRESS_ERROR',
                400
            );
        }
    }

    /**
     * Upload deliverable
     * 
     * POST /api/v1/seller/orders/{order_uuid}/deliverables
     */
    public function uploadDeliverable(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        // Check authorization
        $seller = $order->service?->user ?? $order->product?->user;
        if (!$seller || $seller->id !== $sellerId) {
            return $this->forbidden('Only seller can upload deliverable');
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB max
        ]);

        try {
            $path = $this->orderService->uploadDeliverable($order, $validated['file']);

            return $this->success([
                'deliverable_path' => $path,
                'order' => new OrderResource($order->fresh()),
            ], 'Deliverable uploaded successfully');

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'UPLOAD_ERROR',
                400
            );
        }
    }

    /**
     * Mark order as delivered (for services)
     * 
     * POST /api/v1/seller/orders/{order_uuid}/deliver
     */
    public function deliver(Request $request, Order $order)
    {
        $sellerId = auth()->id();

        // Check authorization
        $belongsToSeller = ($order->product && $order->product->user_id === $sellerId) ||
                           ($order->service && $order->service->user_id === $sellerId);

        if (!$belongsToSeller) {
            return $this->forbidden('You do not have access to this order');
        }

        // Check if order is in processing status
        if ($order->status !== 'processing') {
            return $this->error('Order must be in processing status', [], 'INVALID_STATUS', 400);
        }

        // Check if deliverable is uploaded
        if (!$order->deliverable_path) {
            return $this->error('Please upload deliverable first', [], 'NO_DELIVERABLE', 400);
        }

        try {
            DB::beginTransaction();

            // Update order
            $order->update([
                'delivered_at' => now(),
                'progress' => 100,
                'auto_complete_at' => now()->addDays(3), // Auto complete after 3 days if buyer doesn't confirm
            ]);

            // Create progress update
            $order->progressUpdates()->create([
                'progress' => 100,
                'note' => 'Order delivered and ready for buyer confirmation',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Order marked as delivered'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'DELIVER_ERROR',
                400
            );
        }
    }
}

