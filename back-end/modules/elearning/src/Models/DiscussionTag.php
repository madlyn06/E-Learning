<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $discussion_id
 * @property int $tag_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Discussion $discussion
 * @property-read \Modules\Elearning\Models\Tag $tag
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag whereDiscussionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DiscussionTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DiscussionTag extends Pivot
{
    protected $table = 'elearning__discussion_tags';

    protected $fillable = [
        'discussion_id',
        'tag_id'
    ];

    /**
     * Get the discussion
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class, 'discussion_id');
    }

    /**
     * Get the tag
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }
}
