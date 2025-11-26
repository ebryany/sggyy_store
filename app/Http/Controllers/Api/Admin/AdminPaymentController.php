<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\PaymentResource;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends BaseApiController
{
    protected PaymentService $paymentService;
    protected OrderService $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    /**
     * Get all payments (admin view)
     * 
     * GET /api/v1/admin/payments
     * 
     * Query params:
     * - status: filter by status (pending|verified|rejected)
     * - method: filter by payment method
     * - q: search by order_number, user name
     * - sort: latest|oldest
     * - page, per_page
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.user', 'order.product', 'order.service']);

        // Search
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->whereHas('order', function ($orderQuery) use ($search) {
                $orderQuery->where('order_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%{$search}%")
                                 ->orWhere('email', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Filter by method if provided
        if ($request->filled('method')) {
            $query->where('method', $request->input('method'));
        }

        // Paginate
        $payments = $this->paginate($query, $request);

        return $this->successCollection(
            PaymentResource::collection($payments)
        );
    }

    /**
     * Get single payment details (admin view)
     * 
     * GET /api/v1/admin/payments/{payment_uuid}
     */
    public function show(Payment $payment)
    {
        $payment->load([
            'order.user',
            'order.product',
            'order.service',
            'verifier',
        ]);

        return $this->success(
            new PaymentResource($payment)
        );
    }

    /**
     * Verify payment (admin approval)
     * 
     * PATCH /api/v1/admin/payments/{payment_uuid}/verify
     */
    public function verify(Request $request, Payment $payment)
    {
        if ($payment->status === 'verified') {
            return $this->error('Payment already verified', [], 'ALREADY_VERIFIED', 400);
        }

        if ($payment->status !== 'pending') {
            return $this->error('Only pending payments can be verified', [], 'INVALID_STATUS', 400);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Verify payment
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Update order status
            $order = $payment->order;
            
            if ($order->type === 'product') {
                // Product orders: Complete immediately after payment
                $this->orderService->updateStatus($order, 'completed', 'Payment verified by admin', 'admin');
            } else {
                // Service orders: Move to paid, waiting for seller to accept
                $this->orderService->updateStatus($order, 'paid', 'Payment verified by admin', 'admin');
            }

            // Create escrow if needed
            if (!$order->escrow) {
                $escrowService = app(\App\Services\EscrowService::class);
                $escrowService->createEscrow($order, $payment);
            }

            DB::commit();

            return $this->success(
                new PaymentResource($payment->fresh(['order', 'verifier'])),
                'Payment verified successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'VERIFICATION_ERROR',
                400
            );
        }
    }

    /**
     * Reject payment (admin rejection)
     * 
     * PATCH /api/v1/admin/payments/{payment_uuid}/reject
     */
    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return $this->error('Only pending payments can be rejected', [], 'INVALID_STATUS', 400);
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Reject payment
            $payment->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['reason'],
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Update order status (keep as pending for buyer to reupload proof)
            $order = $payment->order;
            $order->history()->create([
                'status' => $order->status,
                'notes' => "Payment rejected: {$validated['reason']}",
                'changed_by' => auth()->id(),
            ]);

            DB::commit();

            return $this->success(
                new PaymentResource($payment->fresh(['order', 'verifier'])),
                'Payment rejected successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'REJECTION_ERROR',
                400
            );
        }
    }

    /**
     * Get payments statistics
     * 
     * GET /api/v1/admin/payments/statistics
     */
    public function statistics()
    {
        $stats = [
            'total_payments' => Payment::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'verified_payments' => Payment::where('status', 'verified')->count(),
            'rejected_payments' => Payment::where('status', 'rejected')->count(),
            'total_amount_verified' => Payment::where('status', 'verified')
                ->join('orders', 'payments.order_id', '=', 'orders.id')
                ->sum('orders.total'),
            'payment_methods' => [
                'wallet' => Payment::where('method', 'wallet')->count(),
                'bank_transfer' => Payment::where('method', 'bank_transfer')->count(),
                'qris' => Payment::where('method', 'qris')->count(),
                'xendit' => Payment::whereNotNull('xendit_invoice_id')->count(),
            ],
        ];

        return $this->success($stats);
    }

    /**
     * Bulk verify payments
     * 
     * POST /api/v1/admin/payments/bulk-verify
     */
    public function bulkVerify(Request $request)
    {
        $validated = $request->validate([
            'payment_uuids' => ['required', 'array', 'min:1'],
            'payment_uuids.*' => ['required', 'string', 'exists:payments,uuid'],
        ]);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($validated['payment_uuids'] as $uuid) {
            try {
                $payment = Payment::where('uuid', $uuid)->first();
                
                if ($payment && $payment->status === 'pending') {
                    DB::beginTransaction();

                    $payment->update([
                        'status' => 'verified',
                        'verified_at' => now(),
                        'verified_by' => auth()->id(),
                    ]);

                    $order = $payment->order;
                    if ($order->type === 'product') {
                        $this->orderService->updateStatus($order, 'completed', 'Payment verified by admin (bulk)', 'admin');
                    } else {
                        $this->orderService->updateStatus($order, 'paid', 'Payment verified by admin (bulk)', 'admin');
                    }

                    DB::commit();
                    $successCount++;
                } else {
                    $failedCount++;
                    $errors[] = "Payment {$uuid} is not in pending status";
                }
            } catch (\Exception $e) {
                DB::rollBack();
                $failedCount++;
                $errors[] = "Payment {$uuid} failed: " . $e->getMessage();
            }
        }

        return $this->success([
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
        ], "Bulk verification completed: {$successCount} verified, {$failedCount} failed");
    }
}

