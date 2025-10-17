<?php

use Newnet\Acl\Http\Controllers\Admin\ProfileController;
use Newnet\Acl\Http\Controllers\Admin\RoleController;
use Newnet\Acl\Http\Controllers\Admin\UserController;

Route::name('acl.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('role', RoleController::class);
        Route::resource('user', UserController::class);
    });

Route::prefix('profile')
    ->group(function () {
        Route::get('', [ProfileController::class, 'index'])
            ->name('acl.admin.profile.index');

        Route::post('', [ProfileController::class, 'update'])
            ->name('acl.admin.profile.update');

        Route::get('lang/{locale}', [ProfileController::class, 'language'])
            ->name('acl.admin.profile.language');
    });
