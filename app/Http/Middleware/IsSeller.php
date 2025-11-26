<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSeller
{
    /**
     * Handle an incoming request.
     * 
     * 🔒 SECURITY: Check if user is verified seller
     * User must have:
     * 1. Role = 'seller' (set after admin verification)
     * 2. Seller verification status = 'verified' (admin approved)
     * 
     * If user is not verified, redirect to verification page
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Admin can bypass (for admin access to seller features)
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has seller role
        if (!$user->isSeller()) {
            // User belum jadi seller, redirect ke verification page
            return redirect()
                ->route('seller.verification.index')
                ->with('error', 'Anda harus terverifikasi sebagai seller terlebih dahulu.');
        }

        // Check if seller verification is verified
        // 🔒 CRITICAL: Clear all caches and refresh to get latest data
        \Illuminate\Support\Facades\Cache::forget('user_' . $user->id);
        $user->refresh();
        
        // Unset and reload relationship to ensure fresh data
        $user->unsetRelation('sellerVerification');
        $user->load('sellerVerification');
        
        $verification = $user->sellerVerification;
        
        // 🔍 DEBUG: Log verification status for troubleshooting
        \Illuminate\Support\Facades\Log::info('IsSeller middleware check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'has_verification' => $verification !== null,
            'verification_status' => $verification?->status,
            'is_verified' => $verification && $verification->status === 'verified',
        ]);
        
        if (!$verification || $verification->status !== 'verified') {
            // User punya role seller tapi verification belum verified
            // Redirect ke verification page dengan pesan sesuai status
            $message = match($verification?->status) {
                'pending' => 'Verifikasi seller Anda sedang dalam review. Silakan tunggu persetujuan admin.',
                'reviewing' => 'Verifikasi seller Anda sedang direview oleh admin.',
                'rejected' => 'Verifikasi seller Anda ditolak. Silakan perbaiki dan kirim ulang.',
                default => 'Anda harus menyelesaikan verifikasi seller terlebih dahulu.',
            };

            \Illuminate\Support\Facades\Log::warning('Seller access denied - verification not verified', [
                'user_id' => $user->id,
                'verification_status' => $verification?->status,
                'message' => $message,
            ]);

            return redirect()
                ->route('seller.verification.index')
                ->with('error', $message);
        }

        return $next($request);
    }
}
