<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Media\Traits\HasMediaTrait;
use Newnet\Seo\Traits\SeoableTrait;

/**
 * Newnet\Cms\Models\Story
 *
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property bool $is_active
 * @property string|null $layout
 * @property string|null $options
 * @property string|null $author_type
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Cms\Models\StoryItem[] $storyItems
 * @property-read int|null $story_items_count
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\Story newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\Story newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\Story query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereAuthorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\Story whereUpdatedAt($value)
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @mixin \Eloquent
 */
class StorySetting extends Model
{
    use CacheableTrait;
    use HasMediaTrait;
    use SeoableTrait;

    protected $table = 'cms__story_settings';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'author_type',
        'author_id',
        'options',
        'image',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function author()
    {
        return $this->morphTo();
    }

    public function storyItems()
    {
        return $this->hasMany(StoryItem::class);
    }

    public function getLayoutAttribute($value)
    {
        return $value ?: config('cms.story.default');
    }

    public function setImageAttribute($value)
    {
        static::saved(function ($model) use ($value) {
            $model->syncMedia($value, 'image');
        });
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function getUrl()
    {
        return route('cms.web.stories.detail', $this->id);
    }
}
