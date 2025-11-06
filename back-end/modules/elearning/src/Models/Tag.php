<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $color
 * @property bool $is_active
 * @property int $usage_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\DiscussionTag> $discussionTags
 * @property-read int|null $discussion_tags_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Discussion> $discussions
 * @property-read int|null $discussions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag popular($limit = 10)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tag whereUsageCount($value)
 * @mixin \Eloquent
 */
class Tag extends Model
{
    protected $table = 'elearning__tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
        'usage_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_count' => 'integer'
    ];

    /**
     * Get discussions that use this tag
     */
    public function discussions(): BelongsToMany
    {
        return $this->belongsToMany(Discussion::class, 'elearning__discussion_tags', 'tag_id', 'discussion_id');
    }

    /**
     * Get discussion tags pivot table
     */
    public function discussionTags(): HasMany
    {
        return $this->hasMany(DiscussionTag::class, 'tag_id');
    }

    /**
     * Scope for active tags
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for popular tags
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('usage_count', 'desc')->limit($limit);
    }
}
