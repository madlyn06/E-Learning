<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Cms\Models\Rating
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Rating query()
 * @property int $id
 * @property int $stars
 * @property string|null $comment
 * @property string $user
 * @property bool $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereUser($value)
 * @property int $post_id
 * @property-read \Newnet\Cms\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder|Rating wherePostId($value)
 * @property string $name
 * @property string $email
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Rating whereName($value)
 * @mixin \Eloquent
 */
class Rating extends Model
{
    use HasFactory;

    protected $table = 'cms__ratings';

    protected $fillable = [
        'stars',
        'comment',
        'post_id',
        'email',
        'name',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
