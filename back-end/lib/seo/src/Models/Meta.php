<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Seo\Models\Meta
 *
 * @property int $id
 * @property string $metable_type
 * @property int $metable_id
 * @property array|null $title
 * @property array|null $description
 * @property array|null $keywords
 * @property string|null $robots
 * @property string|null $canonical
 * @property array|null $og_title
 * @property array|null $og_description
 * @property array|null $twitter_title
 * @property array|null $twitter_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $og_image
 * @property mixed $twitter_image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Model|\Eloquent $metable
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereCanonical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereMetableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereMetableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereTwitterDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereTwitterTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Meta whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class Meta extends Model
{
    use CacheableTrait;
    use HasMediaTrait;
    use TranslatableTrait;

    protected $table = 'seo__metas';

    protected $fillable = [
        'metable_type',
        'metable_id',
        'title',
        'description',
        'keywords',
        'robots',
        'canonical',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
        'og_image',
        'twitter_image',
    ];

    public $translatable = [
        'title',
        'description',
        'keywords',
        'og_title',
        'og_description',
        'twitter_title',
        'twitter_description',
    ];

    protected $medias = [
        'og_image',
        'twitter_image',
    ];

    public function metable()
    {
        return $this->morphTo();
    }

    public function setOgImageAttribute($value)
    {
        static::saved(function ($model) use ($value) {
            $model->syncMedia($value, 'og_image');
        });
    }

    public function getOgImageAttribute()
    {
        return $this->getFirstMedia('og_image');
    }

    public function setTwitterImageAttribute($value)
    {
        static::saved(function ($model) use ($value) {
            $model->syncMedia($value, 'twitter_image');
        });
    }

    public function getTwitterImageAttribute()
    {
        return $this->getFirstMedia('twitter_image');
    }
}
