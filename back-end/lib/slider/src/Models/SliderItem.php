<?php

namespace Newnet\Slider\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Slider\Models\SliderItem
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
 * @property-read \Newnet\Slider\Models\Slider $slider
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\SliderItem newModelQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\SliderItem newQuery()
 * @method static \Rinvex\Cacheable\EloquentBuilder|\Newnet\Slider\Models\SliderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereAttributes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereSliderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Slider\Models\SliderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SliderItem extends Model
{
    use HasMediaTrait;
    use CacheableTrait;
    use TranslatableTrait;

    protected $table = 'slider__slider_items';

    protected $fillable = [
        'slider_id',
        'name',
        'description',
        'content',
        'attributes',
        'is_active',
        'sort_order',
        'image',
        'link',
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

    public function slider()
    {
        return $this->belongsTo(Slider::class);
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
}
