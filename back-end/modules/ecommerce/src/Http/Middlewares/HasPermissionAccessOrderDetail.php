<?php

namespace Modules\Ecommerce\Http\Middlewares;

use Modules\Ecommerce\Exceptions\OrderException;
use Modules\Ecommerce\Models\Order;

class HasPermissionAccessOrderDetail
{
  public function handle($request, $next)
  {
    // Check if user has permission to access order detail
    // $order = Order::whereOrderNo($request->route('orderNo'))->first();

    // if ($order && $order->email == '') {
    // TODO Think about solution prevent on server side with email
    return $next($request);
    // }
    // throw new OrderException('Không tìm thấy đơn hàng.');
  }
}
