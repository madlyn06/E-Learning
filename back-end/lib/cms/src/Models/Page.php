<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Core\Support\Traits\TreeCacheableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Cms\Models\Page
 *
 * @property int $id
 * @property array|null $name
 * @property string|null $slug
 * @property string|null $page_layout
 * @property array|null $description
 * @property array|null $content
 * @property bool $is_active
 * @property int|null $sort_order
 * @property string|null $author_type
 * @property int|null $author_id
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property-read Model|\Eloquent $author
 * @property-read \Kalnoy\Nestedset\Collection<int, Page> $children
 * @property-read int|null $children_count
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Page|null $parent
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page ancestorsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page ancestorsOf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page applyNestedSetScope(?string $table = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page countErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page defaultOrder(string $dir = 'asc')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page descendantsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page fixSubtree($root)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page getNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page getPlainNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page getTotalErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page hasChildren()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page hasParent()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page isBroken()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page leaves(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page makeGap(int $cut, int $height)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page moveNode($key, $position)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page orWhereDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page orWhereNodeBetween($values)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page orWhereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page query()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page rebuildSubtree($root, array $data, $delete = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page rebuildTree(array $data, $delete = false, $root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page reversed()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page root(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereAncestorOrSelf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereAuthorId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereAuthorType($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereContent($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereCreatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereDescription($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereIsActive($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereIsAfter($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereIsBefore($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereIsLeaf()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereIsRoot()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereLft($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereName($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereNodeBetween($values, $boolean = 'and', $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page wherePageLayout($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereParentId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page wherePublishedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereRgt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereSlug($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereSortOrder($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereUpdatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page withDepth(string $as = 'depth')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page withoutRoot()
 * @property bool $append_internal_link
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereAppendInternalLink($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereLocale(string $column, string $locale)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereLocales(string $column, array $locales)
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @property-read mixed $translations
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Page whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @mixin \Eloquent
 */
class Page extends Model
{
    use HasMediaTrait;
    use SeoableTrait;
    use TreeCacheableTrait;
    use TranslatableTrait;

    protected $table = 'cms__pages';

    protected $fillable = [
        'name',
        'page_layout',
        'description',
        'content',
        'is_active',
        'sort_order',
        'published_at',
        'image',
        'parent_id',
        'append_internal_link',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'append_internal_link' => 'boolean',
    ];

    public function author()
    {
        return $this->morphTo();
    }

    public function getUrl()
    {
        return route('cms.web.page.detail', $this->id);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }
}
