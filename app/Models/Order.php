<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'order_number',
        'type',
        'product_id',
        'service_id',
        'total',
        'status',
        'progress',
        'deadline_at',
        'completed_at',
        'accepted_at',
        'delivered_at',
        'auto_complete_at',
        'deliverable_path',
        'task_file_path',
        'needs_revision',
        'revision_count',
        'revision_notes',
        'max_revisions',
        'priority',
        'notes',
        'cancel_reason',
        'download_limit',
        'download_count',
        'download_expires_at',
        'payment_expires_at',
        'escrow_id',
        'is_disputed',
    ];
    
    protected $casts = [
        'deadline_at' => 'datetime',
        'completed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'delivered_at' => 'datetime',
        'auto_complete_at' => 'datetime',
        'download_expires_at' => 'datetime',
        'payment_expires_at' => 'datetime',
        'needs_revision' => 'boolean',
        'is_disputed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
            if (empty($order->order_number)) {
                $order->order_number = 'EBR-' . strtoupper(Str::random(10));
            }
        });

        static::saving(function ($order) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            if (empty($order->uuid)) {
                $order->uuid = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function history()
    {
        return $this->hasMany(OrderHistory::class);
    }

    public function sellerEarning()
    {
        return $this->hasOne(SellerEarning::class);
    }

    public function escrow()
    {
        return $this->belongsTo(Escrow::class);
    }
    
    public function messages()
    {
        return $this->hasMany(OrderMessage::class)->orderBy('created_at', 'asc');
    }
    
    public function progressUpdates()
    {
        return $this->hasMany(OrderProgressUpdate::class)->orderBy('created_at', 'desc');
    }
    
    public function productDownloads()
    {
        return $this->hasMany(ProductDownload::class);
    }
    
    /**
     * Check if order can download product (for digital products)
     * 🔒 REKBER FLOW: Allow download saat status processing, waiting_confirmation, atau completed
     * 🔒 SECURITY: Validates expiry and download limit
     */
    public function canDownload(): bool
    {
        // Only product orders can download
        if ($this->type !== 'product') {
            return false;
        }
        
        // 🔒 REKBER FLOW: Allow download if status is processing, waiting_confirmation, or completed
        if (!in_array($this->status, ['processing', 'waiting_confirmation', 'completed'])) {
            return false;
        }
        
        // Check expiry (only for completed orders)
        if ($this->status === 'completed' && $this->download_expires_at && $this->download_expires_at->isPast()) {
            return false;
        }
        
        // Check download limit (only for completed orders)
        if ($this->status === 'completed' && $this->download_limit > 0 && $this->download_count >= $this->download_limit) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Increment download count
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
    
    /**
     * Set download expiry (30 days after completed_at by default)
     */
    public function setDownloadExpiry(int $days = 30): void
    {
        if ($this->completed_at) {
            $this->update(['download_expires_at' => $this->completed_at->copy()->addDays($days)]);
        }
    }

    /**
     * Get the route key for the model.
     * Use UUID for API routes
     */
    public function getRouteKeyName(): string
    {
        // For API routes, use UUID
        if (request()->is('api/*')) {
            return 'uuid';
        }
        // For web routes, use order_number
        return 'order_number';
    }

    /**
     * Find order by UUID, order_number or id (for backward compatibility)
     */
    public static function findByUuidOrOrderNumber($value)
    {
        // Try UUID first (API format)
        $order = static::where('uuid', $value)->first();
        
        // Try order_number (web format)
        if (!$order) {
            $order = static::where('order_number', $value)->first();
        }
        
        // Fallback to ID if not found (backward compatibility)
        if (!$order && is_numeric($value)) {
            $order = static::find($value);
        }
        
        return $order;
    }

    /**
     * Find order by order_number or id (for backward compatibility)
     */
    public static function findByOrderNumberOrId($value)
    {
        // Try order_number first (new format)
        $order = static::where('order_number', $value)->first();
        
        // Fallback to ID if not found (backward compatibility)
        if (!$order && is_numeric($value)) {
            $order = static::find($value);
        }
        
        return $order;
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function canBeRated(): bool
    {
        return $this->isCompleted() && !$this->rating;
    }

    /**
     * Get status label in Bahasa Indonesia
     * 🔒 REKBER FLOW: Mapping status ke label yang user-friendly
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'accepted' => 'Diterima Seller',
            'processing' => 'Diproses',
            'waiting_confirmation' => 'Menunggu Konfirmasi',
            'completed' => 'Selesai',
            'needs_revision' => 'Perlu Revisi',
            'cancelled' => 'Dibatalkan',
            'disputed' => 'Dispute',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'paid' => 'blue',
            'accepted' => 'blue',
            'processing' => 'purple',
            'waiting_confirmation' => 'orange',
            'completed' => 'green',
            'needs_revision' => 'yellow',
            'cancelled' => 'red',
            'disputed' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status badge CSS classes for UI
     */
    public function getStatusBadgeClasses(): string
    {
        $color = $this->getStatusBadgeColor();
        
        return match($color) {
            'yellow' => 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30',
            'blue' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
            'purple' => 'bg-purple-500/20 text-purple-400 border border-purple-500/30',
            'orange' => 'bg-orange-500/20 text-orange-400 border border-orange-500/30',
            'green' => 'bg-green-500/20 text-green-400 border border-green-500/30',
            'red' => 'bg-red-500/20 text-red-400 border border-red-500/30',
            default => 'bg-white/10 text-white/60 border border-white/20',
        };
    }
}

