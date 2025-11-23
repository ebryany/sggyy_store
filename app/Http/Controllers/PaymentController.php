<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\FileUploadSecurityService;
use App\Services\SecurityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private FileUploadSecurityService $fileUploadSecurity
    ) {
        $this->middleware('auth');
    }
    
    /**
     * Admin: List all pending payments
     */
    public function index(Request $request): View
    {
        // Authorization: only admin can access
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
        
        // ✅ PHASE 1 FIX: Validate status parameter with whitelist
        $validStatuses = ['pending', 'verified', 'rejected', 'all'];
        $status = $request->get('status', 'pending');
        
        // Validate status parameter
        if (!in_array($status, $validStatuses)) {
            \Illuminate\Support\Facades\Log::warning('Invalid status parameter in payments.index', [
                'user_id' => auth()->id(),
                'status' => $status,
                'ip' => $request->ip(),
            ]);
            $status = 'pending'; // Default to pending if invalid
        }
        
        $payments = Payment::with(['order.user', 'order.product', 'order.service', 'verifier'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        
        return view('admin.payments.index', compact('payments', 'status'));
    }

    public function uploadProof(Payment $payment, Request $request): RedirectResponse|JsonResponse
    {
        // Load order relationship
        $payment->load('order');
        
        // Authorization: only order owner can upload proof
        if ($payment->order->user_id !== auth()->id()) {
            abort(403);
        }

        // Validate payment status - must be pending
        if ($payment->status !== 'pending') {
            $errorMessage = 'Payment sudah diproses, tidak dapat mengupload bukti lagi';
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return back()->withErrors(['error' => $errorMessage]);
        }

        $request->validate([
            'proof_path' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
        ], [
            'proof_path.required' => 'File bukti pembayaran wajib diupload',
            'proof_path.file' => 'File yang diupload tidak valid',
            'proof_path.mimes' => 'Format file harus JPEG, PNG, JPG, atau PDF',
            'proof_path.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            // ✅ PHASE 2: Enhanced file validation
            $file = $request->file('proof_path');
            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'application/pdf',
            ];
            
            // Enhanced validation with content scanning
            $validationErrors = $this->fileUploadSecurity->validateFile($file, $allowedMimeTypes, 2048);
            if (!empty($validationErrors)) {
                SecurityLogger::logFileUploadEvent('File validation failed', [
                    'payment_id' => $payment->id,
                    'file_name' => $file->getClientOriginalName(),
                    'errors' => $validationErrors,
                ]);
                throw new \Exception(implode(', ', $validationErrors));
            }
            
            // Generate secure filename
            $secureFilename = $this->fileUploadSecurity->generateSecureFilename($file, 'proof');
            
            // Log successful validation
            SecurityLogger::logFileUploadEvent('Payment proof upload validated', [
                'payment_id' => $payment->id,
                'file_name' => $file->getClientOriginalName(),
                'secure_filename' => $secureFilename,
            ]);
            
            $this->paymentService->uploadProof($payment, $file, $secureFilename);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diunggah'
                ]);
            }

            return back()->with('success', 'Bukti pembayaran berhasil diunggah');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function verify(Payment $payment, Request $request): RedirectResponse
    {
        // Authorization: only admin can verify
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $this->paymentService->verifyPayment($payment);
            
            // Clear admin dashboard cache to immediately update pending payments count
            $adminDashboardService = app(\App\Services\AdminDashboardService::class);
            $adminDashboardService->clearCache();

            return back()->with('success', 'Pembayaran berhasil diverifikasi');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(Payment $payment, Request $request): RedirectResponse
    {
        // Authorization: only admin can reject
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->paymentService->rejectPayment($payment, $request->rejection_reason);
            
            // Clear admin dashboard cache to immediately update pending payments count
            $adminDashboardService = app(\App\Services\AdminDashboardService::class);
            $adminDashboardService->clearCache();

            return back()->with('success', 'Pembayaran ditolak');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
