<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class XenditWebhookController extends Controller
{
    public function __construct(
        private XenditService $xenditService
    ) {
        // Disable CSRF for webhook (Xendit uses signature verification instead)
        $this->middleware(\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class);
    }

    /**
     * Handle Xendit webhook
     * 
     * Production-grade webhook handler with:
     * - Signature verification
     * - Idempotency handling
     * - Audit logging
     * - Error handling
     */
    public function handle(Request $request): JsonResponse
    {
        // Log raw payload for audit (before any processing)
        $rawPayload = $request->getContent();
        Log::info('Xendit webhook received', [
            'headers' => $request->headers->all(),
            'payload_size' => strlen($rawPayload),
            'ip' => $request->ip(),
        ]);

        try {
            // Verify webhook signature
            $signature = $request->header('X-Callback-Token');
            if (!$this->xenditService->verifyWebhookSignature($rawPayload, $signature)) {
                Log::warning('Xendit webhook: Invalid signature', [
                    'signature' => $signature,
                    'ip' => $request->ip(),
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Parse payload
            $payload = $request->json()->all();
            
            if (empty($payload)) {
                Log::warning('Xendit webhook: Empty payload');
                return response()->json(['error' => 'Empty payload'], 400);
            }

            // Store raw payload for audit (in payment metadata later)
            $payload['_webhook_received_at'] = now()->toISOString();
            $payload['_webhook_ip'] = $request->ip();

            // Process webhook (idempotent)
            $result = $this->xenditService->handleWebhook($payload);

            // Handle different result statuses
            if (isset($result['status']) && $result['status'] === 'ignored') {
                // E-Wallet webhook that doesn't need processing
                return response()->json([
                    'status' => 'success',
                    'message' => $result['message'] ?? 'Webhook received but ignored (no payment record)',
                    'result' => $result,
                ], 200);
            }

            // Return success for processed webhooks
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed',
                'result' => $result,
            ], 200);

        } catch (\Exception $e) {
            // Log error with full context
            Log::error('Xendit webhook processing failed', [
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
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Xendit Webhook Handler',
            'timestamp' => now()->toISOString(),
        ]);
    }
}

