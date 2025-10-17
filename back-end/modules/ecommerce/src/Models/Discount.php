<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Ecommerce\Models\Discount
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $value
 * @property string|null $description
 * @property string|null $valid_from
 * @property string|null $valid_to
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereValidTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereValue($value)
 * @property array|null $products
 * @property array|null $categories
 * @property int $is_all
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereProducts($value)
 * @property int $is_apply_all
 * @property int|null $max_applies
 * @property int|null $total_applied
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereIsApplyAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereMaxApplies($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereTotalApplied($value)
 * @mixin \Eloquent
 */
class Discount extends Model
{
    protected $table = 'ecommerce__discounts';

    protected $fillable = [
        'name',
        'type',
        'value',
        'valid_from',
        'valid_to',
        'is_active',
        'description',
        'is_apply_all',
        'products',
        'categories',
        'max_applies',
        'total_applied',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'products' => 'array',
        'categories' => 'array',
    ];

    /**
     * Check if the discount is valid.
     *
     * @return bool Returns true if the discount is valid, false otherwise.
     */
    public function isValid()
    {
        if (!$this->valid_from && !$this->valid_to) {
            return true;
        }
        if ($this->valid_from && $this->valid_from < now() && !$this->valid_to) {
            return true;
        }
        if ($this->valid_to && $this->valid_to > now() && !$this->valid_from) {
            return true;
        }
        return $this->valid_from <= now() && $this->valid_to >= now();
    }

    /**
     * Calculate the discount for a given total amount.
     *
     * @param float $totalAmount The total amount to calculate the discount for.
     * @return float The calculated discount.
     */
    public function calculateDiscount($totalAmount)
    {
        if ($this->type === 'percent') {
            return $totalAmount * $this->value / 100;
        }

        return $this->value;
    }
}
