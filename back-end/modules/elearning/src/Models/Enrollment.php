<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property numeric $price_paid
 * @property numeric $original_price
 * @property numeric $discount_amount
 * @property int|null $coupon_id
 * @property string|null $payment_method
 * @property string|null $transaction_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $enrolled_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property int $completion_percentage
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\Coupon|null $coupon
 * @property-read \Modules\Elearning\Models\Course $course
 * @property-read \Modules\Elearning\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment completed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCompletionPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCouponId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereEnrolledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment wherePricePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Enrollment whereUserId($value)
 * @mixin \Eloquent
 */
class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'elearning__enrollments';

    protected $fillable = [
        'user_id',
        'course_id',
        'price_paid',
        'original_price',
        'discount_amount',
        'coupon_id',
        'payment_method',
        'transaction_id',
        'status',
        'enrolled_at',
        'expires_at',
        'completion_percentage',
        'completed_at',
    ];

    protected $casts = [
        'price_paid' => 'float',
        'original_price' => 'float',
        'discount_amount' => 'float',
        'completion_percentage' => 'integer',
        'enrolled_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who enrolled in the course.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course in which the user enrolled.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the coupon used for this enrollment.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Scope a query to only include active enrollments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed enrollments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Check if the enrollment is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && 
               (!$this->expires_at || $this->expires_at->isFuture());
    }

    /**
     * Check if the enrollment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed' || $this->completion_percentage === 100;
    }

    /**
     * Check if the enrollment is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || 
               ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Mark the enrollment as completed.
     */
    public function markAsCompleted(): self
    {
        $this->update([
            'status' => 'completed',
            'completion_percentage' => 100,
            'completed_at' => now(),
        ]);

        return $this;
    }
}
