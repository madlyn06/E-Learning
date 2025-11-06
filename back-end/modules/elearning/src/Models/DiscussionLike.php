<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $likeable
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionLike whereUserId($value)
 * @mixin \Eloquent
 */
class DiscussionLike extends Model
{
    protected $table = 'elearning__discussion_likes';

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id'
    ];

    /**
     * Get the user who liked
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the likeable model (discussion or reply)
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Scope for likes by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('likeable_type', $type);
    }

    /**
     * Check if user has liked a specific item
     */
    public static function hasUserLiked($userId, $likeableType, $likeableId): bool
    {
        return static::where('user_id', $userId)
            ->where('likeable_type', $likeableType)
            ->where('likeable_id', $likeableId)
            ->exists();
    }
}
