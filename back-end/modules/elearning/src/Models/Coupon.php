<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $discount_type
 * @property numeric $discount_value
 * @property numeric|null $minimum_purchase
 * @property numeric|null $maximum_discount
 * @property int|null $usage_limit_per_coupon
 * @property int|null $usage_limit_per_user
 * @property \Illuminate\Support\Carbon|null $starts_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property bool $is_active
 * @property bool $is_one_time
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Modules\Elearning\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Enrollment> $enrollments
 * @property-read int|null $enrollments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\CouponUser> $usages
 * @property-read int|null $usages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereIsOneTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMaximumDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereMinimumPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUsageLimitPerCoupon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Coupon whereUsageLimitPerUser($value)
 * @mixin \Eloquent
 */
class Coupon extends Model
{
    use HasFactory;

    protected $table = 'elearning__coupons';

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'minimum_purchase',
        'maximum_discount',
        'usage_limit_per_coupon',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
        'is_one_time',
        'created_by',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_purchase' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_one_time' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the creator of the coupon.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the coupon usages.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUser::class);
    }

    /**
     * Get the enrollments using this coupon.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid(): bool
    {
        // Check if coupon is active
        if (!$this->is_active) {
            return false;
        }

        // Check if coupon has started
        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        // Check if coupon has expired
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // Check if coupon has reached usage limit
        if ($this->usage_limit_per_coupon && $this->usages()->count() >= $this->usage_limit_per_coupon) {
            return false;
        }

        return true;
    }

    /**
     * Check if the coupon is valid for a specific user.
     */
    public function isValidForUser(User $user): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check if user has already used this coupon the maximum number of times
        if ($this->usage_limit_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        // Check if this is a one-time coupon and the user has already used it
        if ($this->is_one_time && $this->usages()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount for a given price.
     */
    public function calculateDiscount(float $price): float
    {
        // Check if the price meets the minimum purchase requirement
        if ($this->minimum_purchase && $price < $this->minimum_purchase) {
            return 0;
        }

        $discountAmount = 0;

        if ($this->discount_type === 'percentage') {
            $discountAmount = $price * ($this->discount_value / 100);
            
            // Apply maximum discount if set
            if ($this->maximum_discount && $discountAmount > $this->maximum_discount) {
                $discountAmount = $this->maximum_discount;
            }
        } else { // fixed discount
            $discountAmount = $this->discount_value;
            
            // Discount cannot be more than the price
            if ($discountAmount > $price) {
                $discountAmount = $price;
            }
        }

        return $discountAmount;
    }
}
