<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     * 
     * 🔒 SECURITY: Removed 'role' and 'wallet_balance' from fillable
     * These should only be modified through dedicated services/methods
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'role', // 🔒 REMOVED: Prevent privilege escalation via mass assignment
        // 'wallet_balance', // 🔒 REMOVED: Prevent balance manipulation via mass assignment
        'avatar',
        'store_name',
        'store_slug',
        'store_description',
        'store_banner',
        'store_logo',
        'phone',
        'address',
        'social_instagram',
        'social_twitter',
        'social_facebook',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
    ];

    /**
     * The attributes that should be protected from mass assignment.
     *
     * @var list<string>
     * 
     * 🔒 SECURITY: Explicitly guard critical fields
     */
    protected $guarded = [
        'role',           // Only admin can change roles
        'wallet_balance', // Only WalletService can modify balance
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
        ];
    }

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function chatsAsUser1()
    {
        return $this->hasMany(Chat::class, 'user1_id');
    }

    public function chatsAsUser2()
    {
        return $this->hasMany(Chat::class, 'user2_id');
    }

    public function chats()
    {
        return Chat::where('user1_id', $this->id)
            ->orWhere('user2_id', $this->id);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function sellerVerification()
    {
        return $this->hasOne(SellerVerification::class);
    }

    public function sellerEarnings()
    {
        return $this->hasMany(SellerEarning::class, 'seller_id');
    }

    public function sellerWithdrawals()
    {
        return $this->hasMany(SellerWithdrawal::class, 'seller_id');
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user is a verified seller
     * 🔒 SECURITY: User must have seller role AND verified verification status
     */
    public function isVerifiedSeller(): bool
    {
        if (!$this->isSeller()) {
            return false;
        }

        // Refresh relationship to get latest data (important for cloud deployment)
        $this->load('sellerVerification');
        $verification = $this->sellerVerification;
        
        return $verification && $verification->status === 'verified';
    }

    // Store Follower Relationships
    
    /**
     * Users who follow this store
     */
    public function followers()
    {
        return $this->hasMany(StoreFollower::class, 'store_owner_id');
    }

    /**
     * Stores that this user follows
     */
    public function following()
    {
        return $this->hasMany(StoreFollower::class, 'user_id');
    }

    /**
     * Check if authenticated user is following this store
     */
    public function isFollowedBy($userId): bool
    {
        return $this->followers()->where('user_id', $userId)->exists();
    }

    /**
     * Get followers count
     */
    public function followersCount(): int
    {
        return $this->followers()->count();
    }

    /**
     * Generate unique store slug from store_name
     */
    public static function generateStoreSlug(string $storeName, ?int $excludeId = null): string
    {
        $slug = \Illuminate\Support\Str::slug($storeName);
        $originalSlug = $slug;
        $counter = 1;
        
        // Ensure uniqueness
        while (self::where('store_slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get store URL
     */
    public function getStoreUrlAttribute(): ?string
    {
        if (!$this->store_slug) {
            return null;
        }
        return route('store.show', $this->store_slug);
    }
}
