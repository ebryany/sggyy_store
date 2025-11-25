<?php

namespace App\Services;

use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SellerVerificationService
{
    public function __construct(
        private FileUploadSecurityService $fileSecurityService
    ) {
    }

    /**
     * Get verification status for a user
     * 
     * @param User $user
     * @return SellerVerification|null
     */
    public function getVerificationStatus(User $user): ?SellerVerification
    {
        return SellerVerification::where('user_id', $user->id)->first();
    }

    /**
     * Request seller verification
     * 
     * @param User $user
     * @param array $data
     * @param UploadedFile $ktpFile
     * @param UploadedFile $photoFile
     * @return SellerVerification
     * @throws \Exception
     */
    public function requestVerification(
        User $user,
        array $data,
        UploadedFile $ktpFile,
        UploadedFile $photoFile
    ): SellerVerification {
        try {
            return DB::transaction(function () use ($user, $data, $ktpFile, $photoFile) {
                // Check if user already has pending verification
                $existing = SellerVerification::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'reviewing'])
                    ->first();

                if ($existing) {
                    throw new \Exception('Anda sudah memiliki permintaan verifikasi yang sedang diproses');
                }

                // Validate KTP file
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $ktpErrors = $this->fileSecurityService->validateFile($ktpFile, $allowedMimeTypes, 2048);
                if (!empty($ktpErrors)) {
                    throw new \Exception('File KTP tidak valid: ' . implode(', ', $ktpErrors));
                }

                // Validate photo file
                $photoErrors = $this->fileSecurityService->validateFile($photoFile, $allowedMimeTypes, 2048);
                if (!empty($photoErrors)) {
                    throw new \Exception('File foto tidak valid: ' . implode(', ', $photoErrors));
                }

                // Store files
                $ktpPath = $ktpFile->store('verifications/ktp', 'public');
                $photoPath = $photoFile->store('verifications/photos', 'public');

                // Create or update verification
                $verification = SellerVerification::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'status' => 'pending',
                        'ktp_path' => $ktpPath,
                        'photo_path' => $photoPath,
                        'social_account' => $data['social_account'] ?? null,
                        'rejection_reason' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                    ]
                );

                Log::info('Seller verification requested', [
                    'user_id' => $user->id,
                    'verification_id' => $verification->id,
                ]);

                return $verification;
            });
        } catch (\Exception $e) {
            Log::error('Failed to request seller verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal mengirim permintaan verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Verify seller (admin only)
     * 
     * @param SellerVerification $verification
     * @param int $verifiedBy
     * @return SellerVerification
     * @throws \Exception
     */
    public function verify(SellerVerification $verification, int $verifiedBy): SellerVerification
    {
        try {
            return DB::transaction(function () use ($verification, $verifiedBy) {
                $verification->update([
                    'status' => 'verified',
                    'verified_by' => $verifiedBy,
                    'verified_at' => now(),
                ]);

                // Update user role to seller if not already
                $user = $verification->user;
                if (!$user->isSeller() && !$user->isAdmin()) {
                    $user->update(['role' => 'seller']);
                    // Refresh user to clear any cached data
                    $user->refresh();
                }

                // Refresh verification to ensure latest data
                $verification->refresh();
                
                // Clear any cached relationships
                $user->unsetRelation('sellerVerification');

                Log::info('Seller verification approved', [
                    'verification_id' => $verification->id,
                    'user_id' => $verification->user_id,
                    'verified_by' => $verifiedBy,
                ]);

                return $verification->fresh();
            });
        } catch (\Exception $e) {
            Log::error('Failed to verify seller', [
                'verification_id' => $verification->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memverifikasi seller: ' . $e->getMessage());
        }
    }

    /**
     * Reject seller verification (admin only)
     * 
     * @param SellerVerification $verification
     * @param string $rejectionReason
     * @param int $rejectedBy
     * @return SellerVerification
     * @throws \Exception
     */
    public function reject(SellerVerification $verification, string $rejectionReason, int $rejectedBy): SellerVerification
    {
        try {
            $verification->update([
                'status' => 'rejected',
                'rejection_reason' => $rejectionReason,
                'verified_by' => $rejectedBy,
            ]);

            Log::info('Seller verification rejected', [
                'verification_id' => $verification->id,
                'user_id' => $verification->user_id,
                'rejected_by' => $rejectedBy,
                'reason' => $rejectionReason,
            ]);

            return $verification->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to reject seller verification', [
                'verification_id' => $verification->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menolak verifikasi: ' . $e->getMessage());
        }
    }
}

