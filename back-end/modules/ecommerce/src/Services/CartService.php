<?php

namespace Modules\Ecommerce\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Ecommerce\Exceptions\CartException;
use Modules\Ecommerce\Models\Cart;
use Modules\Ecommerce\Models\CartItem;

class CartService
{
  /**
   * Get cart items
   * @param string $cartUUid
   * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
   */
  public function getCart($cartUUid)
  {
    $cart = Cart::whereCartUuid($cartUUid)->first();
    if (!$cart) {
      throw new CartException('Cart does not exist!');
    }
    return $cart;
  }

  /**
   * Handle add a product into cart
   * @param string $cartId
   * @param integer $productId
   * @param integer $price
   * @param integer $quantity
   * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
   * @throws CartException
   */
  public function handleAddToCart($cartId, $productId, $price, $quantity)
  {
    try {
      DB::beginTransaction();
      $cart = Cart::whereCartUuid($cartId)->first();
      if (!$cart) {
        $cart = new Cart();
        $cart->total_price = 0;
        $cart->cart_uuid = Str::uuid();
        $cart->save();
      }
      // Check product is in cart
      $cartItem = CartItem::where(['cart_id' => $cart->id, 'product_id' => $productId])->first();
      if ($cartItem) {
        $cartItem->update([
          'quantity' => $cartItem->quantity + $quantity
        ]);
        $cart->update([
          'total_price' => $cart->total_price + ($price * $quantity)
        ]);
        DB::commit();
        return $cart;
      }

      // Thêm sản phẩm vào giỏ hàng
      $cartItem = new CartItem();
      $cartItem->product_id = $productId;
      $cartItem->quantity = $quantity;
      $cartItem->price = $price;
      $cartItem->total_price = $quantity * $price;
      $cart->cartItems()->save($cartItem);

      // Cập nhật total_price
      $cart->total_price += $price * $quantity;
      $cart->save();
      DB::commit();

      return $cart;
    } catch (CartException $ex) {
      DB::rollBack();
      throw new CartException('Error while add to cart, please try again:: ' . $ex->getMessage());
    }
  }

  /**
   * Remove product from cart
   * @param string $cartId
   * @param integer $productId
   */
  public function removeFromCart($cartId, $productId)
  {
    try {
      DB::beginTransaction();
      $cart = Cart::whereCartUuid($cartId)->first();
      if (!$cart) {
        return response()->json(['message' => 'Cart not found.']);
      }
      $cartItem = $cart->cartItems()->where('product_id', $productId)->first();
      if (!$cartItem) {
        return response()->json(['message' => 'Product not found in cart.']);
      }

      $cartItem->delete();

      $cart->total_price -= $cartItem->price * $cartItem->quantity;
      $cart->save();
      if ($cart->cartItems()->count() == 0) {
        $cart->delete();
      }

      DB::commit();

      return response()->json([
        'message' => 'Product removed from cart successfully.',
        'is_empty' => $cart->cartItems()->count() == 0
      ]);
    } catch (CartException $ex) {
      DB::rollBack();
      throw new CartException('Failed to remove product in cart:: ' . $ex->getMessage());
    }
  }

  /**
   * Updates the quantities of items in the specified cart.
   *
   * @param int $cartId The ID of the cart to be updated.
   * @param array $quantities An associative array where the keys are item IDs
   *                          and the values are the corresponding quantities.
   *
   * @return Cart
   * @throws CartException
   */
  public function updateCart($cartId, $quantities)
  {
    try {
      DB::beginTransaction();
      $cart = Cart::whereCartUuid($cartId)->first();
      if (!$cart) {
        throw new CartException('Cart not found.');
      }
      foreach ($quantities as $productId => $quantity) {
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();
        if (!$cartItem) {
          throw new CartException('Product not found in cart.');
        }
        $cartItem->update([
          'quantity' => $quantity,
          'total_price' => $quantity * $cartItem->price,
        ]);
      }
      $cart->total_price = $cart->cartItems->reduce(function ($total, $cartItem) {
        return $total + $cartItem->quantity * $cartItem->price;
      }, 0);
      $cart->save();
      DB::commit();
      return $cart;
    } catch (CartException $ex) {
      DB::rollBack();
      throw new CartException('Failed to update cart:: ' . $ex->getMessage());
    }
  }
}
