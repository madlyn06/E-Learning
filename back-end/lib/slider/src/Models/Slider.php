<?php

namespace Newnet\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;

/**
 * Newnet\Slider\Models\Slider
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Slider\Models\SliderItem[] $sliderItems
 * @property-read int|null $slider_items_count
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\Slider newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\Slider newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereAuthorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\Slider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Slider extends Model
{
    use CacheableTrait;

    protected $table = 'slider__sliders';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'layout',
        'options',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function author()
    {
        return $this->morphTo();
    }

    public function sliderItems()
    {
        return $this->hasMany(SliderItem::class);
    }

    public function getLayoutAttribute($value)
    {
        return $value ?: config('cms.slider.default');
    }
}
