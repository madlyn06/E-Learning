<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property numeric|null $value
 * @property int $user_id
 * @property int $coupon_id
 * @property int $course_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Coupon $coupon
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CouponUser whereValue($value)
 * @mixin \Eloquent
 */
class CouponUser extends Model
{
    use HasFactory;

    protected $table = 'elearning__coupon_users';

    protected $fillable = [
        'value',
        'user_id',
        'coupon_id',
        'course_id',
    ];

    protected $casts = [
        'value' => 'decimal:4',
    ];

    /**
     * Get the user who used the coupon.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coupon that was used.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the course for which the coupon was used.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
