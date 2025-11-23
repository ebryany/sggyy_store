<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use App\Models\Service;
use App\Services\CheckoutService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private SettingsService $settingsService
    ) {
        $this->middleware('auth');
    }

    public function store(CheckoutRequest $request)
    {
        try {
            // Check maintenance mode
            if ($this->settingsService->isMaintenanceMode() && !auth()->user()->isAdmin()) {
                $errorMessage = 'Platform sedang dalam mode maintenance. Silakan coba lagi nanti.';
                
                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => $errorMessage,
                        'message' => $errorMessage,
                    ], 503);
                }
                
                return back()
                    ->withErrors(['error' => $errorMessage])
                    ->with('error', $errorMessage);
            }

            $validated = $request->validated();

            // Check payment method feature flags
            $paymentMethod = $validated['payment_method'] ?? 'wallet';
            $featureFlags = $this->settingsService->getFeatureFlags();
            
            if ($paymentMethod === 'wallet' && !$featureFlags['enable_wallet']) {
                throw new \Exception('Metode pembayaran wallet tidak tersedia saat ini');
            }
            
            if ($paymentMethod === 'bank_transfer' && !$featureFlags['enable_bank_transfer']) {
                throw new \Exception('Metode pembayaran transfer bank tidak tersedia saat ini');
            }
            
            if ($paymentMethod === 'qris' && !$featureFlags['enable_qris']) {
                throw new \Exception('Metode pembayaran QRIS tidak tersedia saat ini');
            }

            if ($validated['type'] === 'product') {
                $product = Product::findOrFail($validated['product_id']);
                $order = $this->checkoutService->checkoutProduct($product, $validated);
            } else {
                $service = Service::findOrFail($validated['service_id']);
                $taskFile = $request->hasFile('task_file') ? $request->file('task_file') : null;
                $order = $this->checkoutService->checkoutService($service, $validated, $taskFile);
            }

            // Determine message based on payment method
            $paymentMethod = $validated['payment_method'] ?? 'wallet';
            $message = 'Pesanan berhasil dibuat';
            
            if (in_array($paymentMethod, ['bank_transfer', 'qris'])) {
                $message = 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran Anda.';
            }

            // If AJAX request, return JSON
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'redirect' => route('orders.show', $order),
                ]);
            }

            return redirect()
                ->route('orders.show', $order)
                ->with('success', $message)
                ->with('upload_proof_required', in_array($paymentMethod, ['bank_transfer', 'qris']));
        } catch (\Exception $e) {
            // Log error with full context for debugging
            \Illuminate\Support\Facades\Log::error('Checkout failed', [
                'user_id' => auth()->id(),
                'type' => $validated['type'] ?? null,
                'product_id' => $validated['product_id'] ?? null,
                'service_id' => $validated['service_id'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Sanitize error message for user display
            $userMessage = $this->sanitizeErrorMessage($e->getMessage());
            
            // If AJAX request, return JSON error
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $userMessage,
                    'message' => $userMessage,
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => $userMessage])
                ->with('error', $userMessage);
        }
    }
    
    /**
     * Sanitize error messages to prevent sensitive info leakage
     */
    private function sanitizeErrorMessage(string $message): string
    {
        // Check if message contains wallet balance info (safe to show)
        if (str_contains($message, 'Saldo wallet tidak mencukupi') || str_contains($message, 'Kekurangan:')) {
            return $message;
        }
        
        // List of safe error messages that can be shown to users
        $safeMessages = [
            'Product tidak tersedia atau stok habis',
            'Jasa tidak tersedia atau tidak aktif',
            'Saldo wallet tidak mencukupi',
            'Product not found',
            'Service not found',
            'Product out of stock',
            'Service is not active',
            'Insufficient wallet balance',
        ];
        
        // If message is in safe list, return as is
        if (in_array($message, $safeMessages)) {
            return $message;
        }
        
        // Otherwise, return generic message
        return 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi atau hubungi support.';
    }
}
