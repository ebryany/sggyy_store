<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user1_id',
        'user2_id',
        'last_message_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($chat) {
            if (empty($chat->uuid)) {
                $chat->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });

        static::saving(function ($chat) {
            // 🔒 CRITICAL: Double-check UUID before saving (fallback)
            if (empty($chat->uuid)) {
                $chat->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        if (request()->is('api/*')) {
            return 'uuid';
        }
        return 'id';
    }

    /**
     * Get user1 (first participant)
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Get user2 (second participant)
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Get all messages in this chat
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the other user in the chat (for current authenticated user)
     */
    public function getOtherUser(?int $currentUserId = null): ?User
    {
        $currentUserId = $currentUserId ?? auth()->id();
        
        if ($this->user1_id === $currentUserId) {
            return $this->user2;
        }
        
        if ($this->user2_id === $currentUserId) {
            return $this->user1;
        }
        
        return null;
    }

    /**
     * Get or create a chat between two users
     */
    public static function getOrCreateChat(int $user1Id, int $user2Id): self
    {
        // Ensure user1_id < user2_id for consistency
        if ($user1Id > $user2Id) {
            [$user1Id, $user2Id] = [$user2Id, $user1Id];
        }

        return self::firstOrCreate(
            [
                'user1_id' => $user1Id,
                'user2_id' => $user2Id,
            ]
        );
    }

    /**
     * Update last message timestamp
     */
    public function updateLastMessageAt(): void
    {
        $this->update(['last_message_at' => now()]);
    }

    /**
     * Get unread messages count for a user
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }
}
