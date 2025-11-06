<?php

use Modules\Ecommerce\Http\Controllers\Api\CartController;
use Modules\Ecommerce\Http\Controllers\Api\CategoryController;
use Modules\Ecommerce\Http\Controllers\Api\CheckoutController;
use Modules\Ecommerce\Http\Controllers\Api\OrderController;
use Modules\Ecommerce\Http\Controllers\Api\ProductController;
use Modules\Ecommerce\Http\Controllers\Api\PaymentMethodController;
use Modules\Ecommerce\Http\Controllers\Api\RatingController;
use Modules\Ecommerce\Http\Middlewares\HasPermissionAccessOrderDetail;

Route::middleware(['api.key'])->group(function () {
  Route::prefix('v1/ecommerce')->group(function () {
    // Category
    Route::get('get-categories', [CategoryController::class, 'getAllActiveCategories'])->name('ecommerce.api.category.getAllActiveCategories');
    Route::get('get-products-in-category/{categoryId}', [CategoryController::class, 'getProductsInCategory'])->name('ecommerce.api.category.getProductsInCategory');

    // Product
    Route::get('products', [ProductController::class, 'getAllProducts'])->name('ecommerce.api.product.getAllProducts');
    Route::get('get-products', [ProductController::class, 'getProducts'])->name('ecommerce.api.product.getProducts');
    Route::get('get-product-detail/{productId}', [ProductController::class, 'getProductDetail'])->name('ecommerce.api.product.getProductDetail');
    Route::get('products-in-category/{slug}', [ProductController::class, 'getProductsInCategory'])->name('ecommerce.api.product.getProductsInCategory');
    Route::get('get-related-products/{productId}', [ProductController::class, 'getRelatedProducts'])->name('ecommerce.api.product.getRelatedProducts');
    // Cart
    Route::get('get-cart/{cartUuid}', [CartController::class, 'getCart'])->name('ecommerce.api.cart.getCart');
    Route::get('get-cart-items/{cartId}', [CartController::class, 'getCartItems'])->name('ecommerce.api.cart.getCartItems');
    Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('ecommerce.api.cart.addToCart');
    Route::post('update-cart', [CartController::class, 'updateCart'])->name('ecommerce.api.cart.updateCart');
    Route::delete('remove-product-in-cart/{productId}/{cartId}', [CartController::class, 'removeProductInCart'])->name('ecommerce.api.cart.removeProductInCart');
    Route::post('apply-discount', [CartController::class, 'applyDiscount'])->name('ecommerce.api.cart.applyDiscount');
    Route::post('remove-discount', [CartController::class, 'removeDiscountInCart'])->name('ecommerce.api.cart.removeDiscountInCart');

    Route::get('payment-methods', [PaymentMethodController::class, 'getAllActivePaymentMethods'])->name('ecommerce.api.payment.getAllPayMethods');
    // Checkout
    Route::post('place-order', [CheckoutController::class, 'placeOrder'])->name('ecommerce.api.checkout.placeOrder');

    // Order
    Route::get('order-detail/{orderNo}', [OrderController::class, 'orderDetail'])->name('ecommerce.api.order.orderDetail')->middleware(HasPermissionAccessOrderDetail::class);

    // Rating
    Route::get('product-ratings', [RatingController::class, 'index'])->name('ecommerce.api.rating.index');
    Route::post('product-ratings', [RatingController::class, 'store'])->name('ecommerce.api.rating.store');
  });
});
