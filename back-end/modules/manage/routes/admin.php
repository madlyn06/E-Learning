<?php

use Illuminate\Support\Facades\Route;
use Modules\Manage\Http\Controllers\Admin\FileSettingController;

Route::prefix('manage')
    ->name('manage.admin.')
    ->middleware('admin.acl')
    ->group(function () {
      Route::resource('team', \Modules\Manage\Http\Controllers\Admin\TeamController::class);
			Route::resource('service', \Modules\Manage\Http\Controllers\Admin\ServiceController::class);
			Route::resource('faq', \Modules\Manage\Http\Controllers\Admin\FAQController::class);
			Route::resource('client', \Modules\Manage\Http\Controllers\Admin\ClientController::class);
			Route::resource('contact', \Modules\Manage\Http\Controllers\Admin\ContactController::class);
			Route::resource('newsletter', \Modules\Manage\Http\Controllers\Admin\NewslettersController::class);
			Route::resource('seo', \Modules\Manage\Http\Controllers\Admin\SeoController::class);
			Route::resource('reason', \Modules\Manage\Http\Controllers\Admin\ReasonController::class);
			Route::resource('banner', \Modules\Manage\Http\Controllers\Admin\BannerController::class);
			Route::resource('pages', \Modules\Manage\Http\Controllers\Admin\PageController::class);
			Route::resource('file-categories', \Modules\Manage\Http\Controllers\Admin\FileCategoryController::class);
			Route::resource('documents', \Modules\Manage\Http\Controllers\Admin\DocumentController::class);
			Route::resource('brand', \Modules\Manage\Http\Controllers\Admin\BrandController::class);
			Route::resource('portfolio-categories', \Modules\Manage\Http\Controllers\Admin\PortfolioCategoryController::class);
			Route::resource('portfolio-projects', \Modules\Manage\Http\Controllers\Admin\PortfolioProjectController::class);

			Route::get('/file/setting', [FileSettingController::class, 'index'])
			->name('files.setting.index');
			Route::post('/file/save', [FileSettingController::class, 'save'])
            ->name('files.setting.save');
		// ADD_ROUTE_MODEL_HERE //
    });

// Route::prefix('setting')->middleware('admin.can:cms.admin.story.setting.edit')->group(function () {
// 	Route::get('/', [StorySettingController::class, 'index'])
// 			->name('cms.admin.story.setting.index');
Route::post('{id}/submit-reply', [\Modules\Manage\Http\Controllers\Admin\ContactController::class, 'postReply'])
	->name('admin.contact.post-reply');
