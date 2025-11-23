<?php

namespace App\Http\Controllers;

use App\Services\QuotaService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class QuotaController extends Controller
{
    public function __construct(
        private QuotaService $quotaService
    ) {
        // Apply auth middleware to all methods EXCEPT webhook (public endpoint)
        $this->middleware('auth')->except(['webhook']);
    }

    /**
     * Display quota purchase page
     */
    public function index(): View
    {
        $user = auth()->user();
        
        $products = [];
        // Create empty paginator as fallback
        $transactions = \App\Models\QuotaTransaction::where('user_id', $user->id)
            ->whereRaw('1 = 0') // Always false, returns empty result
            ->paginate(50);
        
        try {
            $products = $this->quotaService->getProducts();
            
            // Log untuk debugging
            Log::info('QuotaController: Products loaded', [
                'user_id' => $user->id,
                'providers_count' => is_array($products) ? count($products) : 0,
                'products_structure' => is_array($products) ? array_keys($products) : 'not_array',
            ]);
            
            // Ensure products is always an associative array (object-like)
            if (!is_array($products)) {
                Log::warning('QuotaController: Products is not an array', [
                    'type' => gettype($products),
                    'value' => $products,
                ]);
                $products = [];
            }
            
            // Validate structure - should be associative array with provider keys
            if (is_array($products) && count($products) > 0) {
                // Check if it's a numeric array (wrong format)
                $keys = array_keys($products);
                $isNumericArray = !empty($keys) && is_numeric($keys[0]);
                
                if ($isNumericArray) {
                    Log::warning('QuotaController: Products is numeric array, should be associative', [
                        'count' => count($products),
                        'first_key' => $keys[0] ?? null,
                    ]);
                    // If it's a numeric array, it means grouping failed - convert to empty object
                    $products = [];
                } else {
                    // Valid structure - log success
                    Log::info('QuotaController: Products structure valid', [
                        'providers' => array_keys($products),
                        'providers_count' => count($products),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('QuotaController: Error loading products', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // Show error message but don't break the page
            $errorMessage = $e->getMessage();
            
            // If API key not configured, show specific message
            if (str_contains($errorMessage, 'API key')) {
                $errorMessage = 'API key belum dikonfigurasi. Silakan hubungi admin untuk mengatur API key di Settings → API Settings.';
            } elseif (str_contains($errorMessage, 'Connection') || str_contains($errorMessage, 'reset') || str_contains($errorMessage, 'timeout')) {
                $errorMessage = 'Koneksi ke server API terputus. Pastikan koneksi internet stabil atau server API sedang online. Silakan coba lagi nanti.';
            }
            
            session()->flash('error', $errorMessage);
            $products = []; // Set empty array to prevent errors
        }
        
        // Load transactions separately (should not fail if products fail)
        try {
            $transactions = $this->quotaService->getHistory($user, 50);
        } catch (\Exception $e) {
            Log::error('QuotaController: Error loading transactions', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Use empty paginator for transactions
            $transactions = \App\Models\QuotaTransaction::where('user_id', $user->id)
                ->whereRaw('1 = 0')
                ->paginate(50);
        }

        return view('quota.index', compact('products', 'transactions'));
    }

    /**
     * Purchase quota
     */
    public function purchase(Request $request): RedirectResponse
    {
        $request->validate([
            'provider' => ['required', 'string'],
            'produk' => ['required', 'string'],
            'tujuan' => ['required', 'string', 'regex:/^08\d{9,12}$/'],
            'multi_transaksi' => ['nullable', 'boolean'],
        ], [
            'tujuan.regex' => 'Format nomor tujuan tidak valid. Gunakan format 08xxxxxxxxxx',
        ]);

        try {
            $user = auth()->user();
            
            // Check wallet balance first (will be checked again in service, but good UX to check early)
            // Note: Price will be determined from API response
            
            $transaction = $this->quotaService->purchaseQuota($user, [
                'produk' => $request->produk,
                'tujuan' => $request->tujuan,
                'ref_id' => null, // Let service generate UUID
            ]);

            $message = match($transaction->status) {
                'success' => 'Transaksi berhasil! ' . ($transaction->keterangan ?? ''),
                'processing' => 'Transaksi sedang diproses. ' . ($transaction->keterangan ?? ''),
                'pending' => 'Transaksi dibuat. Menunggu konfirmasi. ' . ($transaction->keterangan ?? ''),
                default => 'Transaksi dibuat. Status: ' . ($transaction->status_text ?? 'Processing'),
            };

            return redirect()
                ->route('quota.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('QuotaController: Purchase failed', [
                'user_id' => auth()->id(),
                'produk' => $request->produk,
                'tujuan' => $request->tujuan,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Check stock (XLA or XDA)
     */
    public function checkStock(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'string', 'in:XLA,XDA'],
        ]);

        try {
            $result = $this->quotaService->checkStock($request->get('type'));
            
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('QuotaController: Check stock failed', [
                'type' => $request->get('type'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook endpoint for status updates
     */
    public function webhook(Request $request): JsonResponse
    {
        $message = $request->query('message') 
            ?? $request->input('message')
            ?? $request->getContent();

        if (empty($message)) {
            Log::warning('QuotaController: Webhook received empty message');
            return response()->json([
                'ok' => false,
                'error' => 'message kosong',
            ], 400);
        }

        try {
            $result = $this->quotaService->processWebhook($message);
            
            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('QuotaController: Webhook processing failed', [
                'message' => $message,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok' => false,
                'error' => 'internal_error',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transaction history (AJAX)
     */
    public function history(): JsonResponse
    {
        try {
            $user = auth()->user();
            $transactions = $this->quotaService->getHistory($user, 50);
            
            return response()->json([
                'success' => true,
                'data' => $transactions->items(),
                'html' => view('quota.partials.transaction-table', [
                    'transactions' => $transactions
                ])->render(),
            ]);
        } catch (\Exception $e) {
            Log::error('QuotaController: History failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel pending transaction
     */
    public function cancel(Request $request, string $refId): RedirectResponse
    {
        try {
            $user = auth()->user();
            $transaction = $this->quotaService->cancelTransaction($user, $refId);

            return redirect()
                ->route('quota.index')
                ->with('success', 'Transaksi berhasil dibatalkan. ' . ($transaction->saldo_akhir > $transaction->saldo_awal ? 'Saldo telah dikembalikan.' : ''));
        } catch (\Exception $e) {
            Log::error('QuotaController: Cancel failed', [
                'user_id' => auth()->id(),
                'ref_id' => $refId,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Refund transaction
     */
    public function refund(Request $request, string $refId): RedirectResponse
    {
        try {
            $user = auth()->user();
            $transaction = $this->quotaService->refundTransaction($user, $refId);

            return redirect()
                ->route('quota.index')
                ->with('success', 'Saldo berhasil dikembalikan sebesar Rp ' . number_format($transaction->harga, 0, ',', '.') . ' ke wallet Anda.');
        } catch (\Exception $e) {
            Log::error('QuotaController: Refund failed', [
                'user_id' => auth()->id(),
                'ref_id' => $refId,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
