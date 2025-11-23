<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Based on migration: 2024_01_01_000006_create_ratings_table
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'service_id',
        'order_id',
        'rating',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * Relationship: User who gave the rating
     * Referenced in: User.php line 98 (hasMany)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Product being rated (nullable)
     * Referenced in: Product.php line 47 (hasMany)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: Service being rated (nullable)
     * Referenced in: Service.php line 44 (hasMany)
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relationship: Order associated with this rating
     * Referenced in: Order.php line 80 (hasOne)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Get ratings for a specific product
     */
    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope: Get ratings for a specific service
     */
    public function scopeForService($query, int $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    /**
     * Scope: Get ratings by rating value (e.g., 5 stars only)
     */
    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Check if rating is valid (1-5)
     */
    public function isValidRating(): bool
    {
        return $this->rating >= 1 && $this->rating <= 5;
    }
}

