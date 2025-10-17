<?php

namespace Modules\Ecommerce\Services;

use Modules\Ecommerce\Exceptions\CartException;
use Modules\Ecommerce\Exceptions\CouponException;
use Modules\Ecommerce\Models\Cart;
use Modules\Ecommerce\Models\Discount;

class CouponService
{
  /**
   * Apply discount to cart
   * @param string $cartId
   * @param integer $discountCode
   * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
   * @throws CartException
   */
  public function applyDiscount($cartId, $discountCode)
  {
    $cart = Cart::whereCartUuid($cartId)->first();
    if (!$cart) {
      throw new CouponException('Không tìm thấy giỏ hàng!');
    }
    if ($cart->discount_id) {
      throw new CouponException('Giỏ hàng đã áp dụng mã giảm giá!');
    }
    $discount = Discount::whereName($discountCode)->first();
    if (!$discount) {
      throw new CouponException('Mã giảm giá không chính xác!');
    }
    if (!$discount->isValid()) {
      throw new CouponException('Mã giảm giá đã hết hạn hoặc chưa đến hạn dùng!');
    }
    // Handle discount
    $isAllowApplyCoupon = $this->isAllowApplyCouponInCart($discount, $cart);
    if (!$isAllowApplyCoupon) {
      throw new CouponException('Mã giảm giá này không áp dụng cho giỏ hàng này!');
    }
    $discountValue = $discount->calculateDiscount($cart->total_price);
    $cart->update([
      'discount_id' => $discount->id,
      'total_price' => $cart->total_price - $discountValue
    ]);
    $discount->update([
      'total_applied' => $cart->total_applied + 1
    ]);
    return $discount;
  }

  /**
   * Check if a coupon is allowed to be applied in a cart.
   *
   * @param Discount $discount The discount object representing the coupon.
   * @param Cart $cart The cart object representing the user's cart.
   * @return bool Returns true if the coupon is allowed to be applied in the cart, false otherwise.
   */
  public function isAllowApplyCouponInCart(Discount $discount, Cart $cart)
  {
    if ($discount->is_apply_all) {
      return true;
    }
    if ($discount->max_applies && $discount->total_applied >= $discount->max_applies) {
      return false;
    }
    $productApplies = $discount->products;
    $cartItems = $cart->cartItems;
    foreach ($cartItems as $cartItem) {
      if (in_array($cartItem->product_id, $productApplies)) {
        return true;
      }
    }
    // TODO: get all categories of product and check
    $categoryApplies = $discount->categories;
    foreach ($cartItems as $cartItem) {
      if (in_array($cartItem->product->category_id, $categoryApplies)) {
        return true;
      }
    }
    return true;
  }

  /**
   * Removes the discount associated with a coupon code from the cart.
   *
   * @param string $couponCode The coupon code to remove the discount for.
   * @param string $cart The cart containing the items.
   * @return void
   */
  public function removeDiscountInCart($couponCode, $cartId)
  {
    $discount = Discount::whereName($couponCode)->first();
    if (!$discount) {
      throw new CouponException('Mã giảm giá không tồn tại!');
    }
    $cart = Cart::whereCartUuid($cartId)->first();
    if (!$cart) {
      throw new CartException('Không tìm thấy giỏ hàng.');
    }
    $totalPriceOrigin = $cart->cartItems->reduce(function($total, $cartItem) {
      return $total + $cartItem->quantity * $cartItem->price;
    }, 0);
    $cart->update([
      'discount_id' => null,
      'total_price' => $totalPriceOrigin,
    ]);
    $discount->update([
      'total_applied' => $discount->total_applied - 1
    ]);
    return $discount;
  }
}
