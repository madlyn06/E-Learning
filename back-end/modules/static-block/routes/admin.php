<?php

use Illuminate\Support\Facades\Route;

Route::prefix('static-block')
    ->name('staticblock.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('static-block', \Modules\StaticBlock\Http\Controllers\Admin\StaticBlockController::class);
		// ADD_ROUTE_MODEL_HERE //
    });
