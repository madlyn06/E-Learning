<?php

use Newnet\Zone\Http\Controllers\Admin\ProvinceController;
use Newnet\Zone\Http\Controllers\Admin\DistrictController;
use Newnet\Zone\Http\Controllers\Admin\TownshipController;
use Newnet\Zone\Http\Controllers\Admin\ImportController;

Route::prefix('zone')->group(function () {
    Route::prefix('province')->group(function () {
        Route::get('', [ProvinceController::class, 'index'])
            ->name('zone.admin.province.index')
            ->middleware('admin.can:zone.admin.province.index');

        Route::get('create', [ProvinceController::class, 'create'])
            ->name('zone.admin.province.create')
            ->middleware('admin.can:zone.admin.province.create');

        Route::post('/', [ProvinceController::class, 'store'])
            ->name('zone.admin.province.store')
            ->middleware('admin.can:zone.admin.province.create');

        Route::get('{id}/edit', [ProvinceController::class, 'edit'])
            ->name('zone.admin.province.edit')
            ->middleware('admin.can:zone.admin.province.edit');

        Route::put('{id}', [ProvinceController::class, 'update'])
            ->name('zone.admin.province.update')
            ->middleware('admin.can:zone.admin.province.edit');

        Route::delete('{id}', [ProvinceController::class, 'destroy'])
            ->name('zone.admin.province.destroy')
            ->middleware('admin.can:zone.admin.province.destroy');

        Route::get('{id}/districts', [ProvinceController::class, 'districts'])
            ->name('zone.admin.district.index')
            ->middleware('admin.can:zone.admin.district.index');
    });

    Route::prefix('district')->group(function () {
        Route::get('{id}/create', [DistrictController::class, 'create'])
            ->name('zone.admin.district.create')
            ->middleware('admin.can:zone.admin.district.create');

        Route::post('{id}/', [DistrictController::class, 'store'])
            ->name('zone.admin.district.store')
            ->middleware('admin.can:zone.admin.district.store');

        Route::get('{id}/edit', [DistrictController::class, 'edit'])
            ->name('zone.admin.district.edit')
            ->middleware('admin.can:zone.admin.district.edit');

        Route::put('{id}', [DistrictController::class, 'update'])
            ->name('zone.admin.district.update')
            ->middleware('admin.can:zone.admin.district.edit');

        Route::delete('{id}', [DistrictController::class, 'destroy'])
            ->name('zone.admin.district.destroy')
            ->middleware('admin.can:zone.admin.district.destroy');

        Route::get('{id}/townships', [TownshipController::class, 'townships'])
            ->name('zone.admin.township.index')
            ->middleware('admin.can:zone.admin.township.index');
    });

    Route::prefix('township')->group(function () {
        Route::get('{id}/create', [TownshipController::class, 'create'])
            ->name('zone.admin.township.create')
            ->middleware('admin.can:zone.admin.township.create');

        Route::post('{id}/', [TownshipController::class, 'store'])
            ->name('zone.admin.township.store')
            ->middleware('admin.can:zone.admin.township.store');

        Route::get('{id}/edit', [TownshipController::class, 'edit'])
            ->name('zone.admin.township.edit')
            ->middleware('admin.can:zone.admin.township.edit');

        Route::put('{id}', [TownshipController::class, 'update'])
            ->name('zone.admin.township.update')
            ->middleware('admin.can:zone.admin.township.edit');

        Route::delete('{id}', [TownshipController::class, 'destroy'])
            ->name('zone.admin.township.destroy')
            ->middleware('admin.can:zone.admin.township.destroy');
    });

    Route::prefix('import')->group(function () {
        Route::get('', [ImportController::class, 'index'])
            ->name('zone.admin.import.index')
            ->middleware('admin.can:zone.admin.import.index');

        Route::post('/', [ImportController::class, 'store'])
            ->name('zone.admin.import.store')
            ->middleware('admin.can:zone.admin.import.index');
    });
});
