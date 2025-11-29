<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\OrderResource;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends BaseApiController
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Process checkout
     * 
     * POST /api/v1/checkout
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => ['required', 'array', 'min:1'],
                'items.*.type' => ['required', 'in:product,service'],
                'items.*.id' => ['required', 'integer'],
                'payment_method' => ['required', 'in:wallet,bank_transfer,qris,xendit_va,xendit_qris,veripay_qris'],
                'notes' => ['nullable', 'string', 'max:1000'],
            ]);

            DB::beginTransaction();

            // Process checkout
            $orders = $this->checkoutService->processCheckout(
                auth()->user(),
                $validated['items'],
                $validated['payment_method'],
                $validated['notes'] ?? null
            );

            // Clear cart after successful checkout
            Session::forget('cart');

            DB::commit();

            return $this->created([
                'orders' => OrderResource::collection($orders),
                'total_orders' => count($orders),
                'message' => 'Checkout completed successfully',
            ], 'Checkout completed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'CHECKOUT_ERROR',
                400
            );
        }
    }
}

