<?php

namespace Modules\Elearning\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $discussion_id
 * @property int $user_id
 * @property int|null $parent_reply_id
 * @property string $content
 * @property bool $is_best_answer
 * @property int $like_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Discussion $discussion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionLike> $likes
 * @property-read int|null $likes_count
 * @property-read DiscussionReply|null $parentReply
 * @property-read \Illuminate\Database\Eloquent\Collection<int, DiscussionReply> $replies
 * @property-read int|null $replies_count
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply bestAnswers()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply topLevel()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereDiscussionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereIsBestAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereLikeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereParentReplyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionReply whereUserId($value)
 * @mixin \Eloquent
 */
class DiscussionReply extends Model
{
    use HasFactory;

    protected $table = 'elearning__discussion_replies';

    protected $fillable = [
        'discussion_id',
        'user_id',
        'parent_reply_id',
        'content',
        'is_best_answer',
        'like_count',
        'is_active'
    ];

    protected $casts = [
        'is_best_answer' => 'boolean',
        'like_count' => 'integer',
        'is_active' => 'boolean'
    ];

    public function discussion(): BelongsTo
    {
        return $this->belongsTo(Discussion::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reply (for nested replies)
     */
    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(DiscussionReply::class, 'parent_reply_id');
    }

    /**
     * Get child replies
     */
    public function replies(): HasMany
    {
        return $this->hasMany(DiscussionReply::class, 'parent_reply_id');
    }

    /**
     * Get reply likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(DiscussionLike::class, 'likeable_id')
            ->where('likeable_type', self::class);
    }

    /**
     * Scope for best answers
     */
    public function scopeBestAnswers($query)
    {
        return $query->where('is_best_answer', true);
    }

    /**
     * Scope for top-level replies
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_reply_id');
    }
}
