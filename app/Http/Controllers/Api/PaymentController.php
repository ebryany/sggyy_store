<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends BaseApiController
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create payment for order
     * 
     * POST /api/v1/payments
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'order_uuid' => ['required', 'string', 'exists:orders,uuid'],
            'method' => ['required', 'in:wallet,bank_transfer,qris'],
        ]);

        try {
            $order = Order::where('uuid', $validated['order_uuid'])->firstOrFail();

            // Check authorization
            if ($order->user_id !== auth()->id()) {
                return $this->forbidden('You do not have access to this order');
            }

            // Check if payment already exists
            if ($order->payment) {
                return $this->error(
                    'Payment already exists for this order',
                    [],
                    'PAYMENT_EXISTS',
                    400
                );
            }

            DB::beginTransaction();

            $payment = $this->paymentService->createPayment($order, $validated['method']);

            DB::commit();

            return $this->created(
                new PaymentResource($payment),
                'Payment created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'PAYMENT_ERROR',
                400
            );
        }
    }

    /**
     * Get payment details
     * 
     * GET /api/v1/payments/{payment_uuid}
     */
    public function show(Payment $payment)
    {
        // Check authorization
        $order = $payment->order;
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this payment');
        }

        return $this->success(
            new PaymentResource($payment->load('verifier'))
        );
    }

    /**
     * Upload payment proof
     * 
     * POST /api/v1/payments/{payment_uuid}/proof
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        // Check authorization
        $order = $payment->order;
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this payment');
        }

        // Check if payment requires proof
        if (!$payment->requiresProof()) {
            return $this->error(
                'This payment method does not require proof',
                [],
                'PROOF_NOT_REQUIRED',
                400
            );
        }

        $validated = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'], // 10MB
        ]);

        try {
            $this->paymentService->uploadProof($payment, $validated['proof']);

            return $this->success(
                new PaymentResource($payment->fresh()),
                'Payment proof uploaded successfully'
            );

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
     * Cancel payment
     * 
     * POST /api/v1/payments/{payment_uuid}/cancel
     */
    public function cancel(Payment $payment)
    {
        // Check authorization
        $order = $payment->order;
        if ($order->user_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this payment');
        }

        // Check if payment can be cancelled
        if ($payment->status !== 'pending') {
            return $this->error(
                'Only pending payments can be cancelled',
                [],
                'INVALID_STATUS',
                400
            );
        }

        try {
            DB::beginTransaction();

            $this->paymentService->cancelPayment($payment);

            DB::commit();

            return $this->success(
                new PaymentResource($payment->fresh()),
                'Payment cancelled successfully'
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
}

