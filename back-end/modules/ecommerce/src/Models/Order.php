<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\Ecommerce\Models\Order
 *
 * @property int $id
 * @property string $order_no
 * @property string $status
 * @property string $total_price
 * @property int|null $discount_id
 * @property string|null $discount_type
 * @property int|null $discount_value
 * @property string $shipping_address
 * @property string|null $billing_address
 * @property int $payment_id
 * @property string $payment_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Ecommerce\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereBillingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @property string $email
 * @property string|null $note
 * @property-read \Modules\Ecommerce\Models\PaymentMethod|null $payment
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNote($value)
 * @property string|null $discount_code
 * @property string|null $discount_amount
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscountCode($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    protected $table = 'ecommerce__orders';

    protected $fillable = [
        'order_no',
        'email',
        'status',
        'note',
        'total_price',
        'discount_id',
        'discount_code',
        'discount_amount',
        'shipping_address',
        'billing_address',
        'payment_status',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
