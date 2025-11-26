<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\SellerVerification;
use App\Services\SellerVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerVerificationController extends BaseApiController
{
    protected SellerVerificationService $verificationService;

    public function __construct(SellerVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Get seller verification status
     * 
     * GET /api/v1/seller/verification/status
     */
    public function status()
    {
        $user = auth()->user();
        $verification = $user->sellerVerification;

        if (!$verification) {
            return $this->success([
                'has_verification' => false,
                'status' => null,
                'message' => 'No verification request found. Please submit verification documents.',
            ]);
        }

        return $this->success([
            'has_verification' => true,
            'status' => $verification->status,
            'verification' => [
                'id' => $verification->id,
                'status' => $verification->status,
                'business_name' => $verification->business_name,
                'business_type' => $verification->business_type,
                'submitted_at' => $verification->created_at->toIso8601String(),
                'reviewed_at' => $verification->reviewed_at?->toIso8601String(),
                'rejection_reason' => $verification->rejection_reason,
            ],
            'message' => $this->getStatusMessage($verification->status),
        ]);
    }

    /**
     * Submit seller verification
     * 
     * POST /api/v1/seller/verification
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if already verified
        if ($user->isSeller() && $user->sellerVerification?->status === 'verified') {
            return $this->error(
                'You are already verified as a seller',
                [],
                'ALREADY_VERIFIED',
                400
            );
        }

        // Check if has pending verification
        if ($user->sellerVerification && $user->sellerVerification->status === 'pending') {
            return $this->error(
                'You already have a pending verification request',
                [],
                'PENDING_VERIFICATION',
                400
            );
        }

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['required', 'in:individual,business'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'id_card' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
            'additional_docs' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        try {
            DB::beginTransaction();

            // Upload documents
            $idCardPath = $request->file('id_card')->store('verifications/id_cards', 'private');
            
            $additionalDocsPath = null;
            if ($request->hasFile('additional_docs')) {
                $additionalDocsPath = $request->file('additional_docs')->store('verifications/additional_docs', 'private');
            }

            // Create or update verification
            $verification = SellerVerification::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => $validated['business_name'],
                    'business_type' => $validated['business_type'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'id_card_path' => $idCardPath,
                    'additional_docs_path' => $additionalDocsPath,
                    'status' => 'pending',
                    'rejection_reason' => null,
                    'reviewed_at' => null,
                    'reviewed_by' => null,
                ]
            );

            DB::commit();

            return $this->created([
                'verification' => [
                    'id' => $verification->id,
                    'status' => $verification->status,
                    'business_name' => $verification->business_name,
                    'business_type' => $verification->business_type,
                    'submitted_at' => $verification->created_at->toIso8601String(),
                ],
            ], 'Verification submitted successfully. Please wait for admin review.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'VERIFICATION_ERROR',
                400
            );
        }
    }

    /**
     * Get status message based on verification status
     */
    protected function getStatusMessage(string $status): string
    {
        return match($status) {
            'pending' => 'Your verification is pending review by admin.',
            'reviewing' => 'Your verification is currently being reviewed.',
            'verified' => 'Your seller account is verified. You can start selling.',
            'rejected' => 'Your verification was rejected. Please check the rejection reason and resubmit.',
            default => 'Unknown status',
        };
    }
}

