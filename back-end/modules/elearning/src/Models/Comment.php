<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\TreeCacheableTrait;

/**
 * Module\Elearning\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $lesson_id
 * @property string $content
 * @property string|null $images
 * @property int $_lft
 * @property int $_rgt
 * @property int|null $parent_id
 * @property int $like
 * @property int $dislike
 * @property bool $is_spam
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Kalnoy\Nestedset\Collection<int, Comment> $children
 * @property-read int|null $children_count
 * @property-read \Module\Elearning\Models\Lesson|null $lesson
 * @property-read Comment|null $parent
 * @property-read \Module\Elearning\Models\User|null $user
 * @method static \Kalnoy\Nestedset\Collection<int, static> all($columns = ['*'])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment ancestorsAndSelf($id, array $columns = [])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment ancestorsOf($id, array $columns = [])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment applyNestedSetScope(?string $table = null)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment countErrors()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment d()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment defaultOrder(string $dir = 'asc')
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment descendantsAndSelf($id, array $columns = [])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment descendantsOf($id, array $columns = [], $andSelf = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment fixSubtree($root)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment fixTree($root = null)
 * @method static \Kalnoy\Nestedset\Collection<int, static> get($columns = ['*'])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment getNodeData($id, $required = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment getPlainNodeData($id, $required = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment getTotalErrors()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment hasChildren()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment hasParent()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment isBroken()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment leaves(array $columns = [])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment makeGap(int $cut, int $height)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment moveNode($key, $position)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment newModelQuery()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment newQuery()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment orWhereAncestorOf(bool $id, bool $andSelf = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment orWhereDescendantOf($id)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment orWhereNodeBetween($values)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment orWhereNotDescendantOf($id)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment query()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment rebuildSubtree($root, array $data, $delete = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment rebuildTree(array $data, $delete = false, $root = null)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment reversed()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment root(array $columns = [])
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereAncestorOf($id, $andSelf = false, $boolean = 'and')
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereAncestorOrSelf($id)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereContent($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereCreatedAt($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereDescendantOf($id, $boolean = 'and', $not = false, $andSelf = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereDescendantOrSelf(string $id, string $boolean = 'and', string $not = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereDislike($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereId($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereImages($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereIsAfter($id, $boolean = 'and')
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereIsBefore($id, $boolean = 'and')
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereIsLeaf()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereIsRoot()
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereIsSpam($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereLessonId($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereLft($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereLike($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereNodeBetween($values, $boolean = 'and', $not = false)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereNotDescendantOf($id)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereParentId($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereRgt($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereUpdatedAt($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment whereUserId($value)
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment withDepth(string $as = 'depth')
 * @method static \DnSoft\Core\Support\TreeCacheableQueryBuilder|Comment withoutRoot()
 * @mixin \Eloquent
 */
class Comment extends Model
{
  use HasFactory;
  use TreeCacheableTrait;

  protected $table = 'elearning__comments';

  protected $fillable = [
    'user_id',
    'lesson_id',
    'content',
    'images',
    'is_spam',
    'parent_id',
  ];

  protected $casts = [
    'is_spam' => 'boolean',
  ];

  public function lesson()
  {
    return $this->belongsTo(Lesson::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
