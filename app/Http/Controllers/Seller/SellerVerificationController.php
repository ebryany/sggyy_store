<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Services\SellerVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerVerificationController extends Controller
{
    public function __construct(
        private SellerVerificationService $verificationService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display seller verification page
     * 🔒 SECURITY: Only accessible to non-verified users (buyers)
     * Verified sellers will be redirected to seller dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // 🔒 SECURITY: If user is already verified seller, redirect to dashboard
        if ($user->isVerifiedSeller()) {
            return redirect()
                ->route('seller.dashboard')
                ->with('info', 'Anda sudah terverifikasi sebagai seller.');
        }
        
        $verification = $this->verificationService->getVerificationStatus($user);
        
        // Use regular app layout for buyers, not seller dashboard layout
        return view('seller.verification.index', compact('verification'));
    }

    /**
     * Store seller verification request
     * 🔒 SECURITY: Only accessible to non-verified users
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // 🔒 SECURITY: If user is already verified seller, redirect
        if ($user->isVerifiedSeller()) {
            return redirect()
                ->route('seller.dashboard')
                ->with('info', 'Anda sudah terverifikasi sebagai seller.');
        }
        
        $request->validate([
            'ktp_path' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'photo_path' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'social_account' => ['nullable', 'string', 'max:255'],
            'store_name' => ['nullable', 'string', 'max:255'],
            'store_description' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            
            // Update user profile with store information
            $user->update([
                'store_name' => $request->store_name ?? $user->store_name,
                'store_description' => $request->store_description ?? $user->store_description,
                'phone' => $request->phone ?? $user->phone,
                'address' => $request->address ?? $user->address,
            ]);
            
            $this->verificationService->requestVerification(
                $user,
                $request->only('social_account'),
                $request->file('ktp_path'),
                $request->file('photo_path')
            );

            return redirect()
                ->route('seller.verification.index')
                ->with('success', 'Permintaan verifikasi berhasil dikirim. Menunggu review admin.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
