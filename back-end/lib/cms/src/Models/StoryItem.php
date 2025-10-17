<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Cms\Models\StoryItem
 *
 * @property int $id
 * @property int $slider_id
 * @property array|null $name
 * @property array|null $description
 * @property array|null $content
 * @property string|null $attributes
 * @property bool $is_active
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $link
 * @property mixed $image
 * @property-read array $translations
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Media\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \Newnet\Cms\Models\Slider $slider
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\StoryItem newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\StoryItem newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Cms\Models\StoryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereSliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Cms\Models\StoryItem whereUpdatedAt($value)
 * @property int $story_id
 * @property string|null $addition_image
 * @property int|null $auto_play_after
 * @property mixed $audio
 * @property-read \Newnet\Cms\Models\Story $story
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereAdditionImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereAutoPlayAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereStoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoryItem whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @mixin \Eloquent
 */
class StoryItem extends Model
{
    use HasMediaTrait;
    use CacheableTrait;
    use TranslatableTrait;

    protected $table = 'cms__story_items';

    protected $fillable = [
        'story_id',
        'name',
        'description',
        'content',
        'is_active',
        'sort_order',
        'image',
        'link',
        'auto_play_after',
        'audio',
        'addition_image',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
        'link',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
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

    public function setAudioAttribute($value)
    {
        static::saved(function ($model) use ($value) {
            $model->syncMedia($value, 'audio');
        });
    }

    public function getAudioAttribute()
    {
        return $this->getFirstMedia('audio');
    }
}
