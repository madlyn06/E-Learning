<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ecommerce\Exceptions\OrderException;
use Modules\Ecommerce\Services\OrderService;

class CheckoutController extends Controller
{
  /**
   * The order service instance.
   *
   * @var \Modules\Ecommerce\Services\OrderService
   */
  protected $orderService;

  public function __construct(OrderService $orderService)
  {
    $this->orderService = $orderService;
  }

  /**
   * Place an order.
   *
   * @param \Illuminate\Http\Request $request The HTTP request object.
   * @return mixed
   */
  public function placeOrder(Request $request)
  {
    if (!$request->has(['cart_id', 'email',])) {
      throw new OrderException('Missing required fields');
    }
    $order = $this->orderService->createOrder($request->all());

    return response()->json([
      'status' => 'success',
      'message' => 'Order created successfully',
      'order_no' => $order->order_no,
    ]);
  }
}
