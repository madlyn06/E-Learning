<?php

use Newnet\Seo\Http\Controllers\Web\AdsController;
use Newnet\Seo\Http\Controllers\Web\ShortCodeController;
use Newnet\Seo\Http\Controllers\Web\SitemapController;
use Newnet\Seo\Http\Controllers\Web\UrlRewriteController;

Route::prefix(LaravelLocalization::setLocale())
    ->middleware([
        'localizationRedirect',
        'seo.friendly',
        'seo.preredirect',
    ])
    ->group(function () {
        Route::get('/', UrlRewriteController::class);
        Route::fallback(UrlRewriteController::class);
    });

Route::get('sitemap.xml', SitemapController::class);
Route::get('seo/pre-redirect/{url}', [UrlRewriteController::class, 'checkPreRedirect']);
Route::get('ads/{id}', [AdsController::class, 'detail'])->name('seo.ads.web.detail');
Route::get('short-codes/{id}', [ShortCodeController::class, 'detail'])->name('seo.short-codes.web.detail');
