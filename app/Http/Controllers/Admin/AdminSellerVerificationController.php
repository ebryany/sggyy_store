<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerVerification;
use App\Services\SellerVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSellerVerificationController extends Controller
{
    public function __construct(
        private SellerVerificationService $verificationService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display pending seller verification requests
     */
    public function index(Request $request): View
    {
        $query = SellerVerification::with('user')
            ->latest();

        if ($request->filled('status')) {
            $validStatuses = ['pending', 'verified', 'rejected'];
            $status = $request->status;
            
            if (in_array($status, $validStatuses)) {
                $query->where('status', $status);
            }
        } else {
            // Default: show pending
            $query->where('status', 'pending');
        }

        $verifications = $query->paginate(15);

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Show verification details
     */
    public function show(SellerVerification $verification): View
    {
        $verification->load('user', 'verifier');
        
        return view('admin.verifications.show', compact('verification'));
    }

    /**
     * Approve seller verification
     */
    public function approve(SellerVerification $verification): RedirectResponse
    {
        if ($verification->status !== 'pending') {
            return back()->withErrors(['error' => 'Verifikasi sudah diproses']);
        }

        try {
            $this->verificationService->verify($verification, auth()->id());
            
            // Refresh verification to get latest data
            $verification->refresh();
            
            // Clear cache for the user (if any)
            $user = $verification->user;
            $user->unsetRelation('sellerVerification');

            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $verification->user_id,
                'message' => "✅ Verifikasi seller Anda telah disetujui! Sekarang Anda dapat menjual produk dan layanan.",
                'type' => 'seller_verified',
                'is_read' => false,
                'notifiable_type' => SellerVerification::class,
                'notifiable_id' => $verification->id,
            ]);

            return back()->with('success', 'Verifikasi seller berhasil disetujui');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyetujui verifikasi: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject seller verification
     */
    public function reject(Request $request, SellerVerification $verification): RedirectResponse
    {
        if ($verification->status !== 'pending') {
            return back()->withErrors(['error' => 'Verifikasi sudah diproses']);
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->verificationService->reject(
                $verification,
                $request->rejection_reason,
                auth()->id()
            );

            // Create notification for user
            \App\Models\Notification::create([
                'user_id' => $verification->user_id,
                'message' => "❌ Verifikasi seller Anda ditolak. Alasan: {$request->rejection_reason}",
                'type' => 'seller_rejected',
                'is_read' => false,
                'notifiable_type' => SellerVerification::class,
                'notifiable_id' => $verification->id,
            ]);

            return back()->with('success', 'Verifikasi seller ditolak');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menolak verifikasi: ' . $e->getMessage()]);
        }
    }
}

