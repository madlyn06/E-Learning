<?php

use Illuminate\Support\Facades\Route;
use Newnet\Seo\Http\Controllers\Admin\AdsController;
use Newnet\Seo\Http\Controllers\Admin\AdsItemController;
use Newnet\Seo\Http\Controllers\Admin\ImportInternalLinkController;
use Newnet\Seo\Http\Controllers\Admin\PreRedirectController;
use Newnet\Seo\Http\Controllers\Admin\ErrorRedirectController;
use Newnet\Seo\Http\Controllers\Admin\InternalLinkController;
use Newnet\Seo\Http\Controllers\Admin\SeoSettingController;
use Newnet\Seo\Http\Controllers\Admin\ShortLinkController;

Route::prefix('seo')
    ->name('seo.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('pre-redirect', PreRedirectController::class);
        Route::resource('error-redirect', ErrorRedirectController::class);

        Route::get('setting', [SeoSettingController::class, 'index'])
            ->name('setting.index');

        Route::post('setting/save', [SeoSettingController::class, 'save'])
            ->name('setting.save');
        
        Route::resource('internal-links', InternalLinkController::class);
        Route::resource('ads', AdsController::class);
        Route::resource('ads-items', AdsItemController::class);
        Route::resource('short-links', ShortLinkController::class);
    });

Route::middleware('admin.acl')->group(function() {
    Route::get('seo/import-internal-links/import', [ImportInternalLinkController::class, 'index'])->name('seo.admin.import-internal-links.index');
    Route::post('seo/import-internal-links/import', [ImportInternalLinkController::class, 'import'])->name('seo.admin.import-internal-links.import');
    Route::delete('seo/internal-links/delete-multiple', [InternalLinkController::class, 'destroyMultipleItems']);
});