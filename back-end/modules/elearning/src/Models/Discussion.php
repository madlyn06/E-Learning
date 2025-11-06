<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $course_id
 * @property int $user_id
 * @property string $title
 * @property string $content
 * @property string $type
 * @property int $is_solved
 * @property int $is_pinned
 * @property int $is_locked
 * @property int $view_count
 * @property int $reply_count
 * @property int $like_count
 * @property int|null $best_answer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\DiscussionReply|null $bestAnswer
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionLike> $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionReply> $replies
 * @property-read int|null $replies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion pinned()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion solved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereBestAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereIsLocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereIsPinned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereIsSolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereLikeCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereReplyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discussion whereViewCount($value)
 * @mixin \Eloquent
 */
class Discussion extends Model
{
    use HasFactory;

    protected $table = 'elearning__discussions';

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'content',
        'is_active',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(DiscussionReply::class);
    }

    /**
     * Get the best answer reply
     */
    public function bestAnswer(): BelongsTo
    {
        return $this->belongsTo(DiscussionReply::class, 'best_answer_id');
    }

    /**
     * Get discussion tags
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'elearning__discussion_tags', 'discussion_id', 'tag_id');
    }

    /**
     * Get discussion likes
     */
    public function likes(): HasMany
    {
        return $this->hasMany(DiscussionLike::class, 'likeable_id')
            ->where('likeable_type', self::class);
    }

    /**
     * Scope for solved discussions
     */
    public function scopeSolved($query)
    {
        return $query->where('is_solved', true);
    }

    /**
     * Scope for pinned discussions
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope for active discussions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get discussion types
     */
    public static function getTypes(): array
    {
        return [
            'general' => 'General Discussion',
            'question' => 'Question',
            'announcement' => 'Announcement',
            'feedback' => 'Feedback',
            'help' => 'Help Request'
        ];
    }
}
