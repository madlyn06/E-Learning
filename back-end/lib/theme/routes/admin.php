<?php

use Newnet\Theme\Http\Controllers\Admin\ThemeManagerController;
use Newnet\Theme\Http\Controllers\Admin\ThemeSettingController;

Route::prefix('theme')
    ->middleware('admin.acl')
    ->group(function () {
        Route::get('manager', [ThemeManagerController::class, 'index'])
            ->name('theme.admin.theme-manager.index');

        Route::post('manager/activate', [ThemeManagerController::class, 'activate'])
            ->name('theme.admin.theme-manager.activate');

        Route::get('setting', [ThemeSettingController::class, 'index'])
            ->name('theme.admin.theme-setting.index');

        Route::post('setting/save', [ThemeSettingController::class, 'save'])
            ->name('theme.admin.theme-setting.save');
});
