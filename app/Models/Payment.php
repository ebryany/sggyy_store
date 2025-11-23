<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'proof_path',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper methods
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    public function getMethodDisplayName(): string
    {
        return match($this->method) {
            'wallet' => 'Saldo Wallet',
            'bank_transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'manual' => 'Manual',
            default => ucfirst(str_replace('_', ' ', $this->method)),
        };
    }
    
    public function getMethodIcon(): string
    {
        return match($this->method) {
            'wallet' => 'currency',
            'bank_transfer' => 'bank',
            'qris' => 'mobile',
            'manual' => 'dollar',
            default => 'credit-card',
        };
    }
    
    public function requiresProof(): bool
    {
        return in_array($this->method, ['bank_transfer', 'qris']);
    }
    
    public function getProofUrl(): ?string
    {
        if (!$this->proof_path) {
            return null;
        }
        
        $disk = config('filesystems.default');
        
        // Check if file exists
        if (!\Illuminate\Support\Facades\Storage::disk($disk)->exists($this->proof_path)) {
            // For OSS/S3, URL might still work even if exists() returns false
            // So we'll still try to return the URL
            if ($disk === 'oss' || $disk === 's3') {
                return \Illuminate\Support\Facades\Storage::disk($disk)->url($this->proof_path);
            }
            return null;
        }
        
        return \Illuminate\Support\Facades\Storage::disk($disk)->url($this->proof_path);
    }
    
    public function isProofImage(): bool
    {
        if (!$this->proof_path) {
            return false;
        }
        
        $extension = strtolower(pathinfo($this->proof_path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
    
    public function isProofPdf(): bool
    {
        if (!$this->proof_path) {
            return false;
        }
        
        $extension = strtolower(pathinfo($this->proof_path, PATHINFO_EXTENSION));
        return $extension === 'pdf';
    }
    
    public function getProofExtension(): ?string
    {
        if (!$this->proof_path) {
            return null;
        }
        
        return strtolower(pathinfo($this->proof_path, PATHINFO_EXTENSION));
    }
}




