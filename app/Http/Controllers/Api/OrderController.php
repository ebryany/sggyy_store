<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseApiController
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get user's orders
     * 
     * GET /api/v1/orders
     * 
     * Query params:
     * - status: filter by status
     * - sort: latest|oldest
     * - page, per_page
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id())
            ->with(['product', 'service', 'payment', 'rating']);

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Paginate
        $orders = $this->paginate($query, $request);

        return $this->successCollection(
            OrderResource::collection($orders)
        );
    }

    /**
     * Create new order (manual)
     * 
     * POST /api/v1/orders
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:product,service'],
            'product_id' => ['required_if:type,product', 'integer', 'exists:products,id'],
            'service_id' => ['required_if:type,service', 'integer', 'exists:services,id'],
            'payment_method' => ['required', 'in:wallet,bank_transfer,qris'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            DB::beginTransaction();

            $order = $this->orderService->createOrder(
                auth()->user(),
                $validated
            );

            DB::commit();

            return $this->created(
                new OrderResource($order->load(['product', 'service', 'payment'])),
                'Order created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'ORDER_ERROR',
                400
            );
        }
    }

    /**
     * Get order details
     * 
     * GET /api/v1/orders/{order_uuid}
     */
    public function show(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this order');
        }

        $order->load([
            'product.user',
            'service.user',
            'payment',
            'rating',
            'messages',
            'progressUpdates'
        ]);

        return $this->success(
            new OrderResource($order)
        );
    }

    /**
     * Cancel order
     * 
     * POST /api/v1/orders/{order_uuid}/cancel
     */
    public function cancel(Request $request, Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this order');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            $this->orderService->cancelOrder($order, $validated['reason']);

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Order cancelled successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'CANCEL_ERROR',
                400
            );
        }
    }

    /**
     * Update service order progress
     * 
     * PATCH /api/v1/orders/{order_uuid}/progress
     */
    public function updateProgress(Request $request, Order $order)
    {
        // Only seller can update progress
        $seller = $order->service?->user;
        if (!$seller || $seller->id !== auth()->id()) {
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
     * POST /api/v1/orders/{order_uuid}/deliverables
     */
    public function uploadDeliverable(Request $request, Order $order)
    {
        // Only seller can upload deliverable
        $seller = $order->service?->user ?? $order->product?->user;
        if (!$seller || $seller->id !== auth()->id()) {
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
     * Get deliverables
     * 
     * GET /api/v1/orders/{order_uuid}/deliverables
     */
    public function getDeliverables(Order $order)
    {
        // Check authorization
        if ($order->user_id !== auth()->id()) {
            $seller = $order->service?->user ?? $order->product?->user;
            if (!$seller || $seller->id !== auth()->id()) {
                return $this->forbidden('You do not have access to this order');
            }
        }

        return $this->success([
            'deliverable_path' => $order->deliverable_path,
            'task_file_path' => $order->task_file_path,
            'has_deliverable' => !empty($order->deliverable_path),
            'has_task_file' => !empty($order->task_file_path),
        ]);
    }

    /**
     * Confirm order completion (buyer)
     * 
     * POST /api/v1/orders/{order_uuid}/confirm
     */
    public function confirmCompletion(Request $request, Order $order)
    {
        // Only buyer can confirm
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('Only buyer can confirm completion');
        }

        try {
            DB::beginTransaction();

            $this->orderService->confirmCompletion($order);

            DB::commit();

            return $this->success(
                new OrderResource($order->fresh()),
                'Order confirmed successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'CONFIRM_ERROR',
                400
            );
        }
    }

    /**
     * Request revision
     * 
     * POST /api/v1/orders/{order_uuid}/revision
     */
    public function requestRevision(Request $request, Order $order)
    {
        // Only buyer can request revision
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('Only buyer can request revision');
        }

        $validated = $request->validate([
            'notes' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->orderService->requestRevision($order, $validated['notes']);

            return $this->success(
                new OrderResource($order->fresh()),
                'Revision requested successfully'
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'REVISION_ERROR',
                400
            );
        }
    }

    /**
     * Open dispute
     * 
     * POST /api/v1/orders/{order_uuid}/dispute
     */
    public function dispute(Request $request, Order $order)
    {
        // Only buyer can open dispute
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('Only buyer can open dispute');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->orderService->openDispute($order, $validated['reason']);

            return $this->success(
                new OrderResource($order->fresh()),
                'Dispute opened successfully'
            );

        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                [],
                'DISPUTE_ERROR',
                400
            );
        }
    }
}

