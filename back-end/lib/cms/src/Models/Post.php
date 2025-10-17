<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Newnet\Cms\Traits\KeywordTrait;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Cms\Traits\HasAppendInternalLinkTrait;
use Newnet\Media\Traits\HasMediaTrait;
use Newnet\Tag\Traits\TaggableTrait;

/**
 * Newnet\Cms\Models\Post
 *
 * @property int $id
 * @property array|null $name
 * @property string|null $slug
 * @property string|null $post_type
 * @property array|null $description
 * @property string|null $content
 * @property bool $is_active
 * @property bool $is_sticky
 * @property int|null $sort_order
 * @property string|null $author_type
 * @property int|null $author_id
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int|null $is_viewed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $author
 * @property \Kalnoy\Nestedset\Collection<int, \Newnet\Cms\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property \Illuminate\Database\Eloquent\Collection<int, \Newnet\Tag\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAuthorType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsSticky($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePostType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAllTags($tags, ?string $group = null, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withAnyTags($tags, ?string $group = null, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutAnyTags()
 * @method static \Illuminate\Database\Eloquent\Builder|Post withoutTags($tags, ?string $group = null, ?string $locale = null)
 * @property bool $append_internal_link
 * @property bool $is_created_story
 * @property int|null $migrate_post_id
 * @property-read \Kalnoy\Nestedset\Collection<int, \Newnet\Cms\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Cms\Models\ContentList> $contentList
 * @property-read int|null $content_list_count
 * @property-read mixed $category
 * @property-read mixed $desc
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAppendInternalLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsCreatedStory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMigratePostId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Cms\Models\Rating> $ratings
 * @property-read int|null $ratings_count
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @property int $has_runned_cron_twice
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereHasRunnedCronTwice($value)
 * @property \Illuminate\Database\Eloquent\Collection<int, \Newnet\Cms\Models\Keyword> $keywords
 * @property-read int|null $keywords_count
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasMediaTrait;
    use TaggableTrait;
    use SeoableTrait;
    use TranslatableTrait;
    use HasAppendInternalLinkTrait;
    use KeywordTrait;

    protected $table = 'cms__posts';

    protected $fillable = [
        'name',
        'post_type',
        'description',
        'content',
        'is_active',
        'is_sticky',
        'sort_order',
        'published_at',
        'categories',
        'image',
        'is_viewed',
        'author_type',
        'author_id',
        'append_internal_link',
        'is_created_story',
        'migrate_post_id',
        'has_runned_cron_twice',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sticky' => 'boolean',
        'published_at' => 'datetime',
        'append_internal_link' => 'boolean',
        'is_created_story' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        $attributes['published_at'] = now();

        parent::__construct($attributes);
    }

    public function author()
    {
        return $this->morphTo();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'cms__post_category');
    }

    public function getUrl()
    {
        return route('cms.web.post.detail', $this->id);
    }

    public function getCategoryAttribute()
    {
        return $this->categories->first();
    }

    public function setCategoriesAttribute($value)
    {
        $value = array_filter($value);

        static::saved(function ($model) use ($value) {
            $model->categories()->sync($value);
        });
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function getDescAttribute()
    {
        return $this->description ?: Str::limit(strip_tags($this->content), 200);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function contentList()
    {
        return $this->hasMany(ContentList::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function faqs()
    {
        return $this->hasMany(FAQ::class);
    }

    public function keywords(): MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'keywordable', 'cms__keywordables', 'keywordable_id', 'keyword_id')
            ->orderBy('sort_order')
            ->withTimestamps();
    }

    public function relatedPostsTag(): array|Collection
    {
        return Post::whereHas('tags', function ($query) {
            $query->whereIn('tags.id', $this->tags->pluck('id'));
        })
        ->where('id', '!=', $this->id)
        ->distinct()
        ->limit(5)
        ->get();
    }
}
