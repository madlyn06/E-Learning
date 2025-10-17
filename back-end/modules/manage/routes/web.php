<?php

use Modules\Manage\Http\Controllers\Web\BrandController;
use Modules\Manage\Http\Controllers\Web\TeamController;
use Modules\Manage\Http\Controllers\Web\ServiceController;
use Modules\Manage\Http\Controllers\Web\FAQController;
use Modules\Manage\Http\Controllers\Web\ClientController;
use Modules\Manage\Http\Controllers\Web\PageController;
use Modules\Manage\Http\Controllers\Web\FileCategoryController;
use Modules\Manage\Http\Controllers\Web\FileController;
use Modules\Manage\Http\Controllers\Web\PortfolioController;

// ADD_WEB_USE_ROUTE_MODEL_HERE //

Route::get('manage/team/{id}', [TeamController::class, 'detail'])->name('manage.web.team.detail');
Route::get('manage/service/{id}', [ServiceController::class, 'detail'])->name('manage.web.service.detail');
Route::get('manage/faq/{id}', [FAQController::class, 'detail'])->name('manage.web.faq.detail');
Route::get('manage/client/{id}', [ClientController::class, 'detail'])->name('manage.web.client.detail');
Route::get('manage/pages/{id}', [PageController::class, 'detail'])->name('manage.web.page.detail');
Route::get('manage/file-category/{id}', [FileCategoryController::class, 'detail'])->name('manage.web.file-category.detail');
Route::get('manage/file/{id}', [FileController::class, 'detail'])->name('manage.web.file.detail');
Route::get('manage/brand/{id}', [BrandController::class, 'detail'])->name('manage.web.brand.detail');
Route::get('manage/portfolio-categories/{id}', [PortfolioController::class, 'categoryDetail'])->name('manage.web.portfolio-categories.detail');
Route::get('manage/portfolio-projects/{id}', [PortfolioController::class, 'projectDetail'])->name('manage.web.portfolio-projects.detail');
