<?php

namespace Modules\Ecommerce\Enums;

class OrderStatus
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVAL = 'approval';
    const STATUS_SHIPING = 'shipping';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_COMPLETED = 'completed';

    public static function getLabel($status): string
    {
        $labels = [
            static::STATUS_PENDING => 'Đang chờ xử lý',
            static::STATUS_APPROVAL => 'Đơn hàng đã được chấp nhận',
            static::STATUS_SHIPING => 'Đơn hàng đang được giao',
            static::STATUS_SHIPPED => 'Đã giao hàng xong',
            static::STATUS_COMPLETED => 'Đã hoàn thành',
        ];
        return $labels[$status];
    }
}
