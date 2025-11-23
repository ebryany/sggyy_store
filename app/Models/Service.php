<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'price',
        'duration_hours',
        'status',
        'image',
        'completed_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Helper methods
    public function averageRating(): float
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the route key for the model.
     * Support both slug and ID for backward compatibility
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Retrieve model by slug or ID
     */
    public static function findBySlugOrId(string $identifier): ?self
    {
        // Try to find by slug first
        $service = self::where('slug', $identifier)->first();
        
        // If not found, try by ID (for backward compatibility)
        if (!$service && is_numeric($identifier)) {
            $service = self::find($identifier);
        }
        
        return $service;
    }

    /**
     * Generate unique slug from title
     */
    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure uniqueness
        while (self::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}





