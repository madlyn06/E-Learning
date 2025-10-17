<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Modules\CommentRealtime\Models\Comment
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $title
 * @property string $content
 * @property int $page_id
 * @property int $is_from_admin
 * @property bool $is_published
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property int $like
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection|Comment[] $children
 * @property-read int|null $children_count
 * @property-read \Modules\CommentRealtime\Models\Page $page
 * @property-read Comment|null $parent
 * @method static \Kalnoy\Nestedset\Collection|static[] all($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Comment d()
 * @method static \Kalnoy\Nestedset\Collection|static[] get($columns = ['*'])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment newModelQuery()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment newQuery()
 * @method static \Illuminate\Database\Query\Builder|Comment onlyTrashed()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIsFromAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereIsShowFrontend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Comment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Comment withoutTrashed()
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $post_id
 * @property int $is_show_frontend
 * @property int $dislike
 * @property-read \Newnet\Cms\Models\Post|null $customer
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment ancestorsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment ancestorsOf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment applyNestedSetScope(?string $table = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment countErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment defaultOrder(string $dir = 'asc')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment descendantsAndSelf($id, array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment fixSubtree($root)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment fixTree($root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment getNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment getPlainNodeData($id, $required = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment getTotalErrors()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment hasChildren()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment hasParent()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment isBroken()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment leaves(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment makeGap(int $cut, int $height)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment moveNode($key, $position)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment orWhereDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment orWhereNodeBetween($values)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment orWhereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment rebuildSubtree($root, array $data, $delete = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment rebuildTree(array $data, $delete = false, $root = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment reversed()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment root(array $columns = [])
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereAncestorOrSelf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereDislike($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereEmail($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereIsAfter($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereIsBefore($id, $boolean = 'and')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereIsLeaf()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereIsPublished($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereIsRoot()
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereName($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereNodeBetween($values, $boolean = 'and', $not = false, $query = null)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment whereNotDescendantOf($id)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment wherePhone($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment wherePostId($value)
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment withDepth(string $as = 'depth')
 * @method static \Kalnoy\Nestedset\QueryBuilder|Comment withoutRoot()
 * @mixin \Eloquent
 */
class Comment extends Model
{
  use SoftDeletes, NodeTrait;

  protected $table = 'cms__comments';

  protected $fillable = [
    'name',
    'email',
    'phone',
    'post_id',
    'parent_id',
    'title',
    'content',
    'is_from_admin',
    'is_published',
    'like',
    'dislike',
  ];

  protected $casts = [
    'is_published' => 'boolean',
  ];

  /**
   * @return BelongsTo
   */
  public function customer()
  {
    return $this->belongsTo(Post::class);
  }
}
