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
     * 🔒 SECURITY: Validates expiry and download limit
     */
    public function canDownload(): bool
    {
        // Only product orders can download
        if ($this->type !== 'product') {
            return false;
        }
        
        // Order must be completed
        if ($this->status !== 'completed') {
            return false;
        }
        
        // Check expiry
        if ($this->download_expires_at && $this->download_expires_at->isPast()) {
            return false;
        }
        
        // Check download limit
        if ($this->download_count >= $this->download_limit) {
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
}

