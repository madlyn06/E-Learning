<?php

namespace Modules\Ecommerce\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Ecommerce\Exceptions\ProductException;
use Modules\Ecommerce\Http\Resources\CartResource;
use Modules\Ecommerce\Http\Resources\DiscountResource;
use Modules\Ecommerce\Models\Cart;
use Modules\Ecommerce\Models\Product;
use Modules\Ecommerce\Services\CartService;
use Modules\Ecommerce\Services\CouponService;

class CartController extends Controller
{
  protected $cartService;
  protected $couponService;

  public function __construct(
    CartService $cartService,
    CouponService $couponService
  )
  {
    $this->cartService = $cartService;
    $this->couponService = $couponService;
  }

  /**
   * Get cart information
   */
  public function getCart($cartUuid)
  {
    /**
     * @var Cart $cart
     */
    $cart = $this->cartService->getCart($cartUuid);
    $originAmount = $cart->cartItems->reduce(function ($carry, $item) {
      return $carry + ($item->price * $item->quantity);
    }, 0);
    return response()->json([
      'cartItems' => CartResource::collection($cart->cartItems ?? []),
      'cart' => [
        'cart_id' => $cart->cart_uuid,
        'total_amount' => format_money($cart->total_price),
        'discount' => $cart->discount ? new DiscountResource($cart->discount) : null,
        'original_amount' => format_money($originAmount),
        'total_discount' => $cart->discount ? format_money($cart->discount->calculateDiscount($originAmount)) : null
      ]
    ]);
  }

  /**
   * Add to cart
   */
  public function addToCart(Request $request)
  {
    $productId = $request->productId;
    $product = Product::find($productId);
    if (!$product) {
      throw new ProductException('Product not found!');
    }
    /**
     * @var Cart $cart
     */
    $cart = $this->cartService->handleAddToCart(
      $request->cartId,
      $productId,
      $product->sale_price ?? $product->origin_price,
      $request->quantity
    );
    return response()->json([
      'status' => true,
      'cart_id' => $cart->cart_uuid,
      'message' => 'Product added to cart successfully.'
    ]);
  }

  /**
   * Remove product from cart
   * @param integer $productId
   */
  public function removeProductInCart($productId, $cartId)
  {
    return $this->cartService->removeFromCart(
      $cartId,
      $productId
    );
  }

  /**
   * Update product and quantity in cart
   */
  public function updateCart(Request $request)
  {
    $cartId = $request->cartId;
    $quantities = $request->quantities;
    $cart = $this->cartService->updateCart($cartId, $quantities);
    return response()->json([
      'status' => true,
      'cartItems' => CartResource::collection($cart->cartItems ?? []),
      'cart' => [
        'cart_id' => $cart->cart_uuid,
        'total_amount' => format_money($cart->total_price),
        // 'discount' => $cart->discount ? new DiscountResource($cart->discount) : null,
        // 'total_discount' => $cart->discount ? format_money($cart->discount->calculateDiscount($originAmount)) : null
      ]
    ]);
  }

  /**
   * Add coupon into cart
   */
  public function applyDiscount(Request $request)
  {
    $discount = $this->couponService->applyDiscount($request->cart, $request->couponCode);
    $cart = Cart::whereCartUuid($request->cart)->first();
    return response()->json([
      'status' => true,
      'discount' => new DiscountResource($discount),
      'message' => 'Discount applied successfully.',
      'totalAmount' => format_money($cart->total_price),
    ]);
  }

  /**
   * Remove a discount from the cart.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function removeDiscountInCart(Request $request)
  {
    $this->couponService->removeDiscountInCart($request->couponCode, $request->cartId);
    $cart = Cart::whereCartUuid($request->cartId)->first();
    return response()->json([
      'status' => true,
      'message' => 'Discount removed successfully.',
      'totalAmount' => format_money($cart->total_price),
    ]);
  }
}
