<?php

use Newnet\Customer\Http\Controllers\Admin\CustomerController;
use Newnet\Customer\Http\Controllers\Admin\GroupController;

Route::prefix('customer')
    ->name('customer.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('customer', CustomerController::class);
        Route::resource('group', GroupController::class);
    });
