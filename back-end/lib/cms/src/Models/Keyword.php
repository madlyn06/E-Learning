<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Newnet\Cms\Traits\KeywordTrait;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * Newnet\Tag\Models\Tag
 *
 * @property int $id
 * @property string $slug
 * @property array $name
 * @property array|null $description
 * @property int $sort_order
 * @property string|null $group
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property mixed $url
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 * @method static Builder|Tag onlyTrashed()
 * @method static Builder|Tag query()
 * @method static Builder|Tag whereCreatedAt($value)
 * @method static Builder|Tag whereDeletedAt($value)
 * @method static Builder|Tag whereDescription($value)
 * @method static Builder|Tag whereGroup($value)
 * @method static Builder|Tag whereId($value)
 * @method static Builder|Tag whereName($value)
 * @method static Builder|Tag whereSlug($value)
 * @method static Builder|Tag whereSortOrder($value)
 * @method static Builder|Tag whereUpdatedAt($value)
 * @method static Builder|Tag withTrashed()
 * @method static Builder|Tag withoutTrashed()
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Cms\Models\Keywordable> $keywordables
 * @property-read int|null $keywordables_count
 * @property-read mixed $translations
 * @method static Builder|Keyword whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static Builder|Keyword whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static Builder|Keyword whereLocale(string $column, string $locale)
 * @method static Builder|Keyword whereLocales(string $column, array $locales)
 * @mixin \Eloquent
 */
class Keyword extends Model
{
    use SeoableTrait;
    use TranslatableTrait;
    protected $table = 'cms__keywords';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'sort_order',
        'group',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        'sort_order' => 'integer',
        'group' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    public function getUrl()
    {
        return route('cms.web.post.detail', $this->slug);
    }

    /**
     * Get first keyword(s) by name or create if not exists.
     *
     * @param mixed       $keywords
     * @param string|null $group
     * @param string|null $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByNameOrCreate($keywords, string $group = null, string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return collect(KeywordTrait::parseDelimitedTags($keywords))->map(function (string $keyword) use ($group, $locale) {
            return static::firstByName($keyword, $group, $locale) ?: static::createByName($keyword, $group, $locale);
        });
    }

    /**
     * Find keyword by name.
     *
     * @param mixed       $keywords
     * @param string|null $group
     * @param string|null $locale
     *
     * @return \Illuminate\Support\Collection
     */
    public static function findByName($keywords, string $group = null, string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return collect(KeywordTrait::parseDelimitedTags($keywords))->map(function (string $keyword) use ($group, $locale) {
            return ($exists = static::firstByName($keyword, $group, $locale)) ? $exists->getKey() : null;
        })->filter()->unique();
    }

    /**
     * Create keyword by name.
     *
     * @param string      $keyword
     * @param string|null $locale
     * @param string|null $group
     *
     * @return static
     */
    public static function createByName(string $keyword, string $group = null, string $locale = null): self
    {
        $locale ??= app()->getLocale();

        return static::create([
            'name' => [$locale => $keyword],
            'group' => $group,
            'slug' => $keyword,
        ]);
    }

    /**
     * Get first tag by name.
     *
     * @param string      $tag
     * @param string|null $group
     * @param string|null $locale
     *
     * @return static|null
     */
    public static function firstByName(string $tag, string $group = null, string $locale = null)
    {
        $locale ??= app()->getLocale();

        return static::query()->where("name->{$locale}", $tag)->when($group, function (Builder $builder) use ($group) {
            return $builder->where('group', $group);
        })->first();
    }

    public function keywordables(): HasMany
    {
        return $this->hasMany(Keywordable::class, 'keyword_id');
    }
}
