<?php

use Modules\Ecommerce\Http\Controllers\Admin\RatingController;

Route::prefix('ecommerce')
	->name('ecommerce.admin.')
	->middleware('admin.acl')
	->group(function () {
		Route::resource('category', \Modules\Ecommerce\Http\Controllers\Admin\CategoryController::class);
		Route::resource('product', \Modules\Ecommerce\Http\Controllers\Admin\ProductController::class);
		Route::resource('order', \Modules\Ecommerce\Http\Controllers\Admin\OrderController::class);
		Route::resource('payment-methods', \Modules\Ecommerce\Http\Controllers\Admin\PaymentMethodController::class);
		Route::resource('discounts', \Modules\Ecommerce\Http\Controllers\Admin\DiscountController::class);
		// ADD_ROUTE_MODEL_HERE //
	});

Route::post('ecommerce/ratings/publish', [RatingController::class, 'publish'])
        ->name('ecommerce.admin.ratings.publish')
        ->middleware('admin.can:ecommerce.admin.ratings.publish');