<?php

use Newnet\Menu\Http\Controllers\Admin\MenuController;
use Newnet\Menu\Http\Controllers\Admin\MenuItemController;

Route::name('menu.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('menu', MenuController::class);
    });

Route::prefix('menu')
    ->name('menu.admin.')
    ->middleware('admin.can:menu.admin.menu.edit')
    ->group(function () {
        Route::resource('menu-item', MenuItemController::class);

        Route::put('update-tree', [MenuItemController::class, 'updateTree'])
            ->name('menu.admin.menu-item.update-tree');
    });
