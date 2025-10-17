<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Support\Facades\DB;
use Modules\Ecommerce\Enums\OrderStatus;
use Modules\Ecommerce\Enums\PaymentStatus;
use Modules\Ecommerce\Events\OrderEvent;
use Modules\Ecommerce\Exceptions\OrderException;
use Modules\Ecommerce\Models\Order;
use Modules\Ecommerce\Models\OrderItem;
use Modules\Ecommerce\Utils\StringUtil;

class OrderService
{
  /**
   * Creates a new order.
   *
   * @param array $data The data for creating the order.
   * @return Order
   * @throws \Throwable
   */
  public function createOrder($data = [])
  {
    $cart = app(CartService::class)->getCart($data['cart_id']);
    if (!$cart) {
      throw new OrderException('Cart does not exist!');
    }
    // Get latest order
    $latestOrder = Order::latest()->first();
    try {
      DB::beginTransaction();
      $dataCreate = [
        'order_no' => StringUtil::generateOrderNo($latestOrder ? $latestOrder->id + 1 : 1),
        'email' => $data['email'],
        'status' => OrderStatus::STATUS_PENDING,
        'total_price' => $cart->total_price,
        'shipping_address' => json_encode($this->buildAddress($data)),
        'billing_address' => json_encode($this->buildAddress($data)),
        'payment_status' => PaymentStatus::UNPAID,
        'note' => $data['note'] ?? null,
      ];
      if ($cart->discount) {
        $dataCreate['discount_id'] = $cart->discount->id;
        $dataCreate['discount_code'] = $cart->discount->name;
        $dataCreate['discount_amount'] = $cart->discount->calculateDiscount($cart->total_price);
      }
      $order = Order::create($dataCreate);
      // Create order items
      $cartItems = $cart->cartItems;

      foreach ($cartItems as $cartItem) {
        OrderItem::create([
          'order_id' => $order->id,
          'product_id' => $cartItem->product_id,
          'product_name' => $cartItem->product->name,
          'quantity' => $cartItem->quantity,
          'price' => $cartItem->price,
          'total_price' => $cartItem->quantity * $cartItem->price,
        ]);
      }
      // Delete cart
      $cart->delete();
      event(new OrderEvent($order));
      DB::commit();
      return $order;
    } catch (OrderException $th) {
      DB::rollBack();
      throw $th;
    }
  }

  /**
   * Builds an address based on the given data.
   *
   * @param array $data The data used to build the address.
   * @return array
   */
  private function buildAddress($data)
  {
    return [
      'fullname' => $data['fullname'],
      'email' => $data['email'],
      'phone' => $data['phone'],
      'address' => $data['address'],
    ];
  }

  /**
   * Get order detail
   * @param string $orderNo
   * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
   */
  public function getOrderDetail($orderNo)
  {
    return Order::whereOrderNo($orderNo)->first();
  }
}
