<?php

use Modules\Ecommerce\Http\Controllers\Web\CategoryController;
use Modules\Ecommerce\Http\Controllers\Web\ProductController;
use Modules\Ecommerce\Http\Controllers\Web\OrderController;
use Modules\Ecommerce\Http\Controllers\Web\OrderItemController;
use Modules\Ecommerce\Http\Controllers\Web\CustomerGroupController;
use Modules\Ecommerce\Http\Controllers\Web\CustomerController;
use Modules\Ecommerce\Http\Controllers\Web\CategoryProductController;
// ADD_WEB_USE_ROUTE_MODEL_HERE //

Route::get('ecommerce/category/{id}', [CategoryController::class, 'detail'])->name('ecommerce.web.category.detail');
Route::get('ecommerce/product/{id}', [ProductController::class, 'detail'])->name('ecommerce.web.product.detail');
Route::get('ecommerce/order/{id}', [OrderController::class, 'detail'])->name('ecommerce.web.order.detail');
Route::get('ecommerce/order-item/{id}', [OrderItemController::class, 'detail'])->name('ecommerce.web.order-item.detail');
Route::get('ecommerce/customer-group/{id}', [CustomerGroupController::class, 'detail'])->name('ecommerce.web.customer-group.detail');
Route::get('ecommerce/customer/{id}', [CustomerController::class, 'detail'])->name('ecommerce.web.customer.detail');
Route::get('ecommerce/category-product/{id}', [CategoryProductController::class, 'detail'])->name('ecommerce.web.category-product.detail');
// ADD_WEB_ROUTE_MODEL_HERE //
