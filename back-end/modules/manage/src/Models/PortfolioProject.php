<?php

namespace Modules\Manage\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Modules\Manage\Models\Service
 *
 * @property int $id
 * @property array|null $name
 * @property array|null $description
 * @property array|null $content
 * @property string|null $icon
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $url
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @property int|null $category_id
 * @property string|null $client_name
 * @property string|null $year
 * @property string|null $author
 * @property string|null $location
 * @property mixed $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Modules\Manage\Models\PortfolioCategory|null $portfolioCategory
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereClientName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioProject whereYear($value)
 * @mixin \Eloquent
 */
class PortfolioProject extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'manage__portfolio_projects';

    protected $fillable = [
        'name',
        'description',
        'content',
        'icon',
        'image',
        'is_active',
        'client_name',
        'year',
        'author',
        'location',
        'category_id',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getUrl()
    {
        return route('manage.web.portfolio-projects.detail', $this->id);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function portfolioCategory()
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }
}
