<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'post_id',
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Find or create the conversation between two users for a given post.
     * Participants are normalised (smaller id first) so the unique key dedupes.
     */
    public static function firstOrCreateBetween(?int $postId, int $userA, int $userB): self
    {
        $one = min($userA, $userB);
        $two = max($userA, $userB);

        return static::firstOrCreate(
            ['post_id' => $postId, 'user_one_id' => $one, 'user_two_id' => $two],
            ['last_message_at' => now()],
        );
    }

    public function involves(int $userId): bool
    {
        return $this->user_one_id === $userId || $this->user_two_id === $userId;
    }

    public function otherUser(int $userId): ?User
    {
        return $this->user_one_id === $userId ? $this->userTwo : $this->userOne;
    }
}
