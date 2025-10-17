<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Cms\Models\ContentList
 *
 * @property int $id
 * @property string $name
 * @property string $target
 * @property int|null $parent_id
 * @property int|null $post_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentList whereUpdatedAt($value)
 * @property-read \Newnet\Cms\Models\Post|null $post
 * @mixin \Eloquent
 */
class ContentList extends Model
{
    use HasFactory;

    protected $table = 'cms__content_list';

    protected $fillable = [
        'name',
        'target',
        'parent_id',
        'post_id',
    ];

    public function getChildrensContentList()
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
