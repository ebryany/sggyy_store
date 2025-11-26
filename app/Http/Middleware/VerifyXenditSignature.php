<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Services\XenditService;

/**
 * Verify Xendit Webhook Signature
 * 
 * Security middleware untuk memverifikasi webhook dari Xendit
 * Menggunakan X-Callback-Token header
 * 
 * Usage:
 * Route::post('/webhooks/xendit/*', ...)->middleware('xendit.signature');
 */
class VerifyXenditSignature
{
    protected XenditService $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get signature from header
        $signature = $request->header('X-Callback-Token');
        
        if (!$signature) {
            Log::warning('Xendit webhook: Missing X-Callback-Token header', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Missing signature',
            ], 401);
        }

        // Get raw payload for signature verification
        $rawPayload = $request->getContent();

        // Verify signature
        if (!$this->xenditService->verifyWebhookSignature($rawPayload, $signature)) {
            Log::warning('Xendit webhook: Invalid signature', [
                'signature' => $signature,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'path' => $request->path(),
                'payload_size' => strlen($rawPayload),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 401);
        }

        // Signature valid, continue
        Log::info('Xendit webhook: Signature verified', [
            'ip' => $request->ip(),
            'path' => $request->path(),
        ]);

        return $next($request);
    }
}

