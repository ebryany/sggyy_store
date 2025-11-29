<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Crypt;

/**
 * Encrypted Cast
 * 
 * 🔒 SECURITY: Encrypts sensitive data at rest in database
 * Automatically encrypts on save and decrypts on retrieve
 */
class Encrypted implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, might be plain text (for migration compatibility)
            // Log and return as-is for now, but this should be migrated
            \Illuminate\Support\Facades\Log::warning('Failed to decrypt value', [
                'key' => $key,
                'model' => get_class($model),
                'error' => $e->getMessage(),
            ]);
            return $value;
        }
    }

    /**
     * Transform the attribute to its underlying model values.
     */
    public function set($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        // If value is already encrypted (starts with base64 pattern), don't encrypt again
        // This prevents double encryption
        if (is_string($value) && preg_match('/^[A-Za-z0-9+\/]{20,}={0,2}$/', $value)) {
            // Might be already encrypted, try to decrypt first
            try {
                Crypt::decryptString($value);
                // If decrypt succeeds, it's already encrypted, return as-is
                return $value;
            } catch (\Exception $e) {
                // Not encrypted, proceed with encryption
            }
        }

        return Crypt::encryptString($value);
    }
}

