<?php

namespace App\Http\Controllers\Api\Webhook;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Xendit Webhook Handler
 * 
 * Following 5 principles:
 * - Isolated namespace (/api/v1/webhooks/xendit/*)
 * - No Sanctum authentication (signature verification only)
 * - Idempotent processing
 * - Audit logging
 * - Error handling with retry support
 */
class XenditWebhookController extends Controller
{
    public function __construct(
        private XenditService $xenditService
    ) {
        // Disable CSRF for webhook (Xendit uses signature verification instead)
        $this->middleware(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
    }

    /**
     * Handle Xendit payment webhook
     * 
     * POST /api/v1/webhooks/xendit/payment
     * 
     * Processes:
     * - Invoice payments (VA, QRIS)
     * - Manual bank transfer confirmations
     * - Payment status updates (PAID, EXPIRED, FAILED)
     * 
     * Security:
     * - Signature verification via X-Callback-Token header
     * - IP whitelist (optional, configure in middleware)
     * - Rate limiting
     */
    public function handlePayment(Request $request): JsonResponse
    {
        return $this->processWebhook($request, 'payment');
    }

    /**
     * Handle Xendit invoice webhook
     * 
     * POST /api/v1/webhooks/xendit/invoice
     * 
     * Processes invoice-specific events:
     * - invoice.paid
     * - invoice.expired
     * - invoice.failed
     */
    public function handleInvoice(Request $request): JsonResponse
    {
        return $this->processWebhook($request, 'invoice');
    }

    /**
     * Handle Xendit disbursement webhook
     * 
     * POST /api/v1/webhooks/xendit/disbursement
     * 
     * Processes seller payout events:
     * - disbursement.completed
     * - disbursement.failed
     */
    public function handleDisbursement(Request $request): JsonResponse
    {
        return $this->processWebhook($request, 'disbursement');
    }

    /**
     * Process webhook with unified handler
     * 
     * @param Request $request
     * @param string $type
     * @return JsonResponse
     */
    protected function processWebhook(Request $request, string $type): JsonResponse
    {
        // Log raw payload for audit (before any processing)
        $rawPayload = $request->getContent();
        Log::info("Xendit {$type} webhook received", [
            'type' => $type,
            'headers' => $request->headers->all(),
            'payload_size' => strlen($rawPayload),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        try {
            // 🔒 SECURITY: Verify webhook signature
            $signature = $request->header('X-Callback-Token');
            if (!$this->xenditService->verifyWebhookSignature($rawPayload, $signature)) {
                Log::warning("Xendit {$type} webhook: Invalid signature", [
                    'type' => $type,
                    'signature' => $signature,
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Parse payload
            $payload = $request->json()->all();
            
            if (empty($payload)) {
                Log::warning("Xendit {$type} webhook: Empty payload", ['type' => $type]);
                return response()->json(['error' => 'Empty payload'], 400);
            }

            // Add audit metadata
            $payload['_webhook_received_at'] = now()->toISOString();
            $payload['_webhook_ip'] = $request->ip();
            $payload['_webhook_type'] = $type;

            // Process webhook (idempotent)
            $result = $this->xenditService->handleWebhook($payload);

            // Handle different result statuses
            if (isset($result['status']) && $result['status'] === 'ignored') {
                // Webhook that doesn't need processing
                Log::info("Xendit {$type} webhook: Ignored", [
                    'type' => $type,
                    'reason' => $result['message'] ?? 'Unknown',
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'] ?? 'Webhook received but ignored',
                    'result' => $result,
                ], 200);
            }

            if (isset($result['status']) && $result['status'] === 'already_processed') {
                // Idempotency check passed - already processed
                Log::info("Xendit {$type} webhook: Already processed (idempotent)", [
                    'type' => $type,
                    'payment_id' => $result['payment_id'] ?? null,
                ]);
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Webhook already processed',
                    'result' => $result,
                ], 200);
            }

            // Return success for processed webhooks
            Log::info("Xendit {$type} webhook: Processed successfully", [
                'type' => $type,
                'result' => $result,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed',
                'result' => $result,
            ], 200);

        } catch (\Exception $e) {
            // Log error with full context
            Log::error("Xendit {$type} webhook processing failed", [
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $request->json()->all(),
                'ip' => $request->ip(),
            ]);

            // Return error (Xendit will retry)
            // Don't return 200 if we want Xendit to retry
            return response()->json([
                'status' => 'error',
                'message' => 'Webhook processing failed',
                'error' => app()->environment('production') ? 'Internal server error' : $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Health check endpoint for webhook
     * 
     * GET /api/v1/webhooks/xendit/health
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Xendit Webhook Handler (API v1)',
            'timestamp' => now()->toISOString(),
            'version' => '1.0.0',
        ]);
    }
}

