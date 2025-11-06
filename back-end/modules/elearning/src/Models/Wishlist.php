<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUserId($value)
 * @mixin \Eloquent
 */
class Wishlist extends Model
{
    protected $table = 'elearning__wishlists';

    protected $fillable = [
        'user_id',
        'course_id',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($wishlist) {
            // Logic to handle notification after wishlist creation
            // This can be implemented later
        });
    }

    /**
     * Get the user that owns the wishlist item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that is wishlisted
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
