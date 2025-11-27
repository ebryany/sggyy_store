<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    public function __construct(
        private FileUploadSecurityService $fileSecurityService
    ) {
    }

    /**
     * Calculate profile completion percentage
     * 
     * @param User $user
     * @return array{percentage: int, missing: array<string>}
     */
    public function getProfileCompletion(User $user): array
    {
        $requiredFields = [
            'name' => 'Nama',
            'email' => 'Email',
        ];

        $recommendedFields = [
            'phone' => 'Nomor HP',
            'address' => 'Alamat',
            'avatar' => 'Foto Profil',
        ];

        $completed = 0;
        $total = count($requiredFields) + count($recommendedFields);
        $missing = [];

        // Check required fields
        foreach ($requiredFields as $field => $label) {
            if (!empty($user->$field)) {
                $completed++;
            } else {
                $missing[] = $label;
            }
        }

        // Check recommended fields
        foreach ($recommendedFields as $field => $label) {
            if (!empty($user->$field)) {
                $completed++;
            } else {
                $missing[] = $label;
            }
        }

        $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;

        return [
            'percentage' => $percentage,
            'missing' => $missing,
        ];
    }

    /**
     * Update user profile
     * 
     * @param User $user
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateProfile(User $user, array $data): User
    {
        try {
            // Only update allowed fields (security: prevent mass assignment of protected fields)
            $allowedFields = [
                'name',
                'email',
                'phone',
                'address',
                'store_name',
                'store_description',
                'social_instagram',
                'social_twitter',
                'social_facebook',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
            ];

            $updateData = array_intersect_key($data, array_flip($allowedFields));

            // If store_name is being updated, regenerate store_slug
            if (isset($updateData['store_name']) && $updateData['store_name'] !== $user->store_name) {
                $updateData['store_slug'] = User::generateStoreSlug($updateData['store_name'], $user->id);
            }

            $user->update($updateData);

            Log::info('Profile updated', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($updateData),
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memperbarui profile: ' . $e->getMessage());
        }
    }

    /**
     * Update user avatar with security validation
     * 
     * @param User $user
     * @param UploadedFile $file
     * @return User
     * @throws \Exception
     */
    public function updateAvatar(User $user, UploadedFile $file): User
    {
        try {
            // Security: Validate file using FileUploadSecurityService
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 2048);

            if (!empty($validationErrors)) {
                throw new \Exception('File tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old avatar if exists
            $storageService = app(\App\Services\StorageService::class);
            if ($user->avatar) {
                $storageService->delete($user->avatar);
            }

            // Store new avatar - Use StorageService for proper OSS integration
            $path = $storageService->store($file, 'avatars');

            // Update user
            $user->update(['avatar' => $path]);

            Log::info('Avatar updated', [
                'user_id' => $user->id,
                'avatar_path' => $path,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update avatar', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memperbarui avatar: ' . $e->getMessage());
        }
    }

    /**
     * Remove user avatar
     * 
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function removeAvatar(User $user): User
    {
        try {
            $storageService = app(\App\Services\StorageService::class);
            if ($user->avatar) {
                $storageService->delete($user->avatar);
            }

            $user->update(['avatar' => null]);

            Log::info('Avatar removed', [
                'user_id' => $user->id,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to remove avatar', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menghapus avatar: ' . $e->getMessage());
        }
    }

    /**
     * Change user password
     * 
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return User
     * @throws \Exception
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): User
    {
        try {
            // Verify current password
            if (!Hash::check($currentPassword, $user->password)) {
                throw new \Exception('Password saat ini tidak benar');
            }

            // Update password
            $user->update([
                'password' => Hash::make($newPassword),
            ]);

            Log::info('Password changed', [
                'user_id' => $user->id,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to change password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal mengubah password: ' . $e->getMessage());
        }
    }

    /**
     * Update store banner with security validation
     * 
     * @param User $user
     * @param UploadedFile $file
     * @return User
     * @throws \Exception
     */
    public function updateStoreBanner(User $user, UploadedFile $file): User
    {
        try {
            // Security: Validate file using FileUploadSecurityService
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 5120); // 5MB max for banners

            if (!empty($validationErrors)) {
                throw new \Exception('File tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old banner if exists
            $storageService = app(\App\Services\StorageService::class);
            if ($user->store_banner) {
                $storageService->delete($user->store_banner);
            }

            // Store new banner - Use StorageService for proper OSS integration
            $path = $storageService->store($file, 'store/banners');

            // Update user
            $user->update(['store_banner' => $path]);

            Log::info('Store banner updated', [
                'user_id' => $user->id,
                'banner_path' => $path,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update store banner', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memperbarui banner toko: ' . $e->getMessage());
        }
    }

    /**
     * Update store logo with security validation
     * 
     * @param User $user
     * @param UploadedFile $file
     * @return User
     * @throws \Exception
     */
    public function updateStoreLogo(User $user, UploadedFile $file): User
    {
        try {
            // Security: Validate file using FileUploadSecurityService
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            $validationErrors = $this->fileSecurityService->validateFile($file, $allowedMimeTypes, 2048); // 2MB max for logos

            if (!empty($validationErrors)) {
                throw new \Exception('File tidak valid: ' . implode(', ', $validationErrors));
            }

            // Delete old logo if exists
            $storageService = app(\App\Services\StorageService::class);
            if ($user->store_logo) {
                $storageService->delete($user->store_logo);
            }

            // Store new logo - Use StorageService for proper OSS integration
            $path = $storageService->store($file, 'store/logos');

            // Update user
            $user->update(['store_logo' => $path]);

            Log::info('Store logo updated', [
                'user_id' => $user->id,
                'logo_path' => $path,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to update store logo', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal memperbarui logo toko: ' . $e->getMessage());
        }
    }

    /**
     * Remove store banner
     * 
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function removeStoreBanner(User $user): User
    {
        try {
            if ($user->store_banner && Storage::disk('public')->exists($user->store_banner)) {
                Storage::disk('public')->delete($user->store_banner);
            }

            $user->update(['store_banner' => null]);

            Log::info('Store banner removed', [
                'user_id' => $user->id,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to remove store banner', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menghapus banner toko: ' . $e->getMessage());
        }
    }

    /**
     * Remove store logo
     * 
     * @param User $user
     * @return User
     * @throws \Exception
     */
    public function removeStoreLogo(User $user): User
    {
        try {
            if ($user->store_logo && Storage::disk('public')->exists($user->store_logo)) {
                Storage::disk('public')->delete($user->store_logo);
            }

            $user->update(['store_logo' => null]);

            Log::info('Store logo removed', [
                'user_id' => $user->id,
            ]);

            return $user->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to remove store logo', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception('Gagal menghapus logo toko: ' . $e->getMessage());
        }
    }
}

