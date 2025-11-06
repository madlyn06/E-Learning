<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property int $id
 * @property array<array-key, mixed>|null $name
 * @property array<array-key, mixed>|null $description
 * @property array<array-key, mixed>|null $content
 * @property bool $is_active
 * @property int|null $sort
 * @property string|null $icon
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Course> $courses
 * @property-read int|null $courses_count
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read Category|null $parent
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category ancestorsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category ancestorsOf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category applyNestedSetScope(?string $table = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category countErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category d()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category defaultOrder(string $dir = 'asc')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category descendantsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category fixSubtree($root)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category getNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category getPlainNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category getTotalErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category hasChildren()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category hasParent()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category isBroken()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category leaves(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category makeGap(int $cut, int $height)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category moveNode($key, $position)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category orWhereDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category orWhereNodeBetween($values)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category orWhereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category query()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category rebuildSubtree($root, array $data, $delete = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category rebuildTree(array $data, $delete = false, $root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category reversed()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category root(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereAncestorOrSelf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereContent($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereCreatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereDescription($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIcon($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIsActive($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIsAfter($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIsBefore($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIsLeaf()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereIsRoot()
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereJsonContainsLocale(string $column, string $locale, ?mixed $value, string $operand = '=')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereJsonContainsLocales(string $column, array $locales, ?mixed $value, string $operand = '=')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereLft($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereLocale(string $column, string $locale)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereLocales(string $column, array $locales)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereName($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereNodeBetween($values, $boolean = 'and', $not = false, $query = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereParentId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereRgt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereSort($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category whereUpdatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category withDepth(string $as = 'depth')
 * @method static \Kalnoy\Nestedset\QueryBuilder<static>|Category withoutRoot()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;
    use NodeTrait;

    protected $table = 'elearning__categories';

    protected $fillable = [
        'name',
        'description',
        'content',
        'parent_id',
        'sort',
        'image',
        'is_active',
        'icon',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the URL for the category
     */
    public function getUrl()
    {
        return route('elearning.web.category.detail', $this->id);
    }

    /**
     * Get the courses in this category
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'elearning__category_course');
    }

    public function getDescendantIds()
    {
        $catIds = $this->descendants()->pluck('id')->toArray();
        $catIds[] = $this->getKey();

        return $catIds;
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
