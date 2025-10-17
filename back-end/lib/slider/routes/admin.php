<?php

use Newnet\Slider\Http\Controllers\Admin\SliderController;
use Newnet\Slider\Http\Controllers\Admin\SliderItemController;

Route::name('slider.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('slider', SliderController::class);
    });


Route::prefix('slider')->group(function () {
    Route::prefix('')->group(function () {
        Route::get('{slider_id}/builder', [SliderItemController::class, 'index'])
            ->name('slider.admin.slider-item.index')
            ->middleware('admin.can:slider.admin.slider.edit');
    });

    Route::prefix('slider-item')->middleware('admin.can:slider.admin.slider.edit')->group(function () {
        Route::get('create', [SliderItemController::class, 'create'])
            ->name('slider.admin.slider-item.create');

        Route::post('/', [SliderItemController::class, 'store'])
            ->name('slider.admin.slider-item.store');

        Route::get('{id}/edit', [SliderItemController::class, 'edit'])
            ->name('slider.admin.slider-item.edit');

        Route::put('{id}', [SliderItemController::class, 'update'])
            ->name('slider.admin.slider-item.update');

        Route::delete('{id}', [SliderItemController::class, 'destroy'])
            ->name('slider.admin.slider-item.destroy');
    });
});
