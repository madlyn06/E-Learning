<?php

namespace Newnet\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Core\Support\Traits\TreeCacheableTrait;
use Newnet\Menu\Contracts\FrontendMenuBuilderInterface;

/**
 * Newnet\Menu\Models\MenuItem
 *
 * @property int $id
 * @property int $menu_id
 * @property array|null $label
 * @property string|null $icon
 * @property string|null $class
 * @property string|null $target
 * @property string|null $menu_builder_class
 * @property string|null $menu_builder_args
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection<int, MenuItem> $children
 * @property-read int|null $children_count
 * @property-read mixed $is_active
 * @property-read mixed $url
 * @property-read \Newnet\Menu\Models\Menu $menu
 * @property-read MenuItem|null $parent
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem ancestorsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem ancestorsOf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem applyNestedSetScope(?string $table = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem countErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem d()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem defaultOrder(string $dir = 'asc')
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem descendantsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem fixSubtree($root)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem getNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem getPlainNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem getTotalErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem hasChildren()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem hasParent()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem isBroken()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem leaves(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem makeGap(int $cut, int $height)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem moveNode($key, $position)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem newQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem orWhereDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem orWhereNodeBetween($values)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem orWhereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem query()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem rebuildSubtree($root, array $data, $delete = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem rebuildTree(array $data, $delete = false, $root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem reversed()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem root(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereAncestorOrSelf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereClass($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereCreatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereIcon($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereIsAfter($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereIsBefore($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereIsLeaf()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereIsRoot()
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereLabel($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereLft($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereMenuBuilderArgs($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereMenuBuilderClass($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereMenuId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereNodeBetween($values, $boolean = 'and', $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereParentId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereRgt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereTarget($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem whereUpdatedAt($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem withDepth(string $as = 'depth')
 * @method static \Kalnoy\Nestedset\QueryBuilder|MenuItem withoutRoot()
 * @mixin \Eloquent
 */
class MenuItem extends Model
{
    use TreeCacheableTrait;
    use TranslatableTrait;

    protected $table = 'menu__menu_items';

    protected $fillable = [
        'menu_id',
        'label',
        'icon',
        'class',
        'target',
        'menu_builder_class',
        'menu_builder_args',
        'parent_id',
    ];

    public $translatable = [
        'label',
    ];

    /** @var FrontendMenuBuilderInterface */
    protected $menuBuilder;

    public function getMenuBuilderArgsAttribute($value)
    {
        return json_decode($value, true) ?: [];
    }

    public function setMenuBuilderArgsAttribute($value)
    {
        $this->attributes['menu_builder_args'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function getUrl()
    {
        if ($this->getMenuBuilder()) {
            return $this->getMenuBuilder()->getFrontendUrl();
        }

        return null;
    }

    public function isActive()
    {
        if ($this->getMenuBuilder()) {
            return $this->getMenuBuilder()->isActive();
        }

        return false;
    }

    public function getMenuBuilder()
    {
        if (!$this->menuBuilder && class_exists($this->menu_builder_class)) {
            $this->menuBuilder = app($this->menu_builder_class)->setArgs($this->menu_builder_args);
        }

        return $this->menuBuilder;
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['label'] = $this->label;

        return $arr;
    }

    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

    public function activeClass($className = 'active')
    {
        if ($this->is_active) {
            return $className;
        }

        return '';
    }

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }
}
