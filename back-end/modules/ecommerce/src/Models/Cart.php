<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Ecommerce\Models\Cart
 *
 * @property int $id
 * @property string $total_price
 * @property string $cart_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Ecommerce\Models\CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCartUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @property int|null $discount_id
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereDiscountId($value)
 * @property-read \Modules\Ecommerce\Models\Discount|null $discount
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected $table = 'ecommerce__carts';

    protected $fillable = [
        'cart_uuid',
        'total_price',
        'discount_id',
    ];

    protected $casts = [
        'total_price' => 'float',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
