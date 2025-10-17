<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Modules\Ecommerce\Models\Product
 *
 * @property int $id
 * @property array|null $name
 * @property float|null $origin_price
 * @property float|null $sale_price
 * @property array|null $description
 * @property array|null $content
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Kalnoy\Nestedset\Collection<int, \Modules\Ecommerce\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read mixed $category
 * @property mixed $gallery
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereOriginPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @property-read mixed $related_products
 * @mixin \Eloquent
 */
class Product extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'ecommerce__products';

    protected $fillable = [
        'name',
        'origin_price',
        'sale_price',
        'description',
        'content',
        'is_active',
        'image',
        'gallery',
        'categories',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'origin_price' => 'float',
        'sale_price' => 'float',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ecommerce__category_products');
    }

    public function getUrl()
    {
        return route('ecommerce.web.product.detail', $this->id);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function setGalleryAttribute($value)
    {
        $this->mediaAttributes['gallery'] = $value;
    }

    public function getGalleryAttribute()
    {
        return $this->getMedia('gallery');
    }

    public function getCategoriesAttribute()
    {
        return $this->categories()->get();
    }

    public function setCategoriesAttribute($value)
    {
        $value = array_filter($value);

        static::saved(function ($model) use ($value) {
            $model->categories()->sync($value);
        });
    }

    public function getRelatedProductsAttribute()
    {
        $relatedProducts = null;
        $categories = $this->categories()->pluck('id');
        if ($this->categories()->count() > 0) {
          $relatedProducts = Product::whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('id', $categories);
          })->where('id', '!=', $this->id)->inRandomOrder()->limit(2)->get();
        }
        return $relatedProducts;
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
