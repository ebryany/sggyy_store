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
        // Refresh user to get latest data (important for cloud deployment)
        $user->refresh();
        
        // Unset and reload relationship to ensure fresh data
        $user->unsetRelation('sellerVerification');
        $user->load('sellerVerification');
        
        $verification = $user->sellerVerification;
        
        if (!$verification || $verification->status !== 'verified') {
            // User punya role seller tapi verification belum verified
            // Redirect ke verification page dengan pesan sesuai status
            $message = match($verification?->status) {
                'pending' => 'Verifikasi seller Anda sedang dalam review. Silakan tunggu persetujuan admin.',
                'reviewing' => 'Verifikasi seller Anda sedang direview oleh admin.',
                'rejected' => 'Verifikasi seller Anda ditolak. Silakan perbaiki dan kirim ulang.',
                default => 'Anda harus menyelesaikan verifikasi seller terlebih dahulu.',
            };

            return redirect()
                ->route('seller.verification.index')
                ->with('error', $message);
        }

        return $next($request);
    }
}
