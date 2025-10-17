<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Exceptions\OrderException;
use Modules\Ecommerce\Http\Resources\OrderDetailResource;
use Modules\Ecommerce\Models\Order;
use Modules\Ecommerce\Services\OrderService;

class OrderController extends Controller
{
  protected $orderService;

  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
  }

  /**
   * Retrieves the details of an order.
   *
   * @param string $orderNo The order number.
   * @return OrderDetailResource
   */
  public function orderDetail($orderNo)
  {
    $order = $this->orderService->getOrderDetail($orderNo);
    if (!$order) {
      throw new OrderException('Order not found!.');
    }
    return new OrderDetailResource($order ?? new Order());
  }
}
