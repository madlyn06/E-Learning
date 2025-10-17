<?php

use Illuminate\Support\Facades\Route;
use Newnet\Seo\Http\Controllers\Api\AdsController;
// use Newnet\Seo\Http\Controllers\Api\InternalLinkController;
use Newnet\Seo\Http\Controllers\Api\SitemapController;

Route::get('sitemap/{type}', [SitemapController::class, 'getSitemap'])->name('seo.sitemap.getSitemap');
// Route::get('seo/internal-links', [InternalLinkController::class, 'getInternalLinks'])->name('seo.api.internal-link.getInternalLinks');
Route::post('seo/ads/check-code', [AdsController::class, 'checkCode'])->name('seo.api.ads.checkCode');
Route::get('seo/ads/get-content/{code}', [AdsController::class, 'getContent'])->name('seo.api.ads.getContent');
