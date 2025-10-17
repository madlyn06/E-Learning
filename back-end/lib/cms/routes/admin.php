<?php

use Illuminate\Support\Facades\Route;
use Newnet\Cms\Http\Controllers\Admin\PageController;
use Newnet\Cms\Http\Controllers\Admin\PostController;
use Newnet\Cms\Http\Controllers\Admin\CategoryController;
use Newnet\Cms\Http\Controllers\Admin\CmsCrawlSettingController;
use Newnet\Cms\Http\Controllers\Admin\CmsSettingController;
use Newnet\Cms\Http\Controllers\Admin\StoryController;
use Newnet\Cms\Http\Controllers\Admin\CommentController;
use Newnet\Cms\Http\Controllers\Admin\DocumentationController;
use Newnet\Cms\Http\Controllers\Admin\RatingController;
use Newnet\Cms\Http\Controllers\Admin\StoryItemController;
use Newnet\Cms\Http\Controllers\Admin\StorySettingController;
use Newnet\Cms\Http\Controllers\Admin\SyncController;
use Newnet\Cms\Http\Controllers\Admin\ContentListController;
use Newnet\Cms\Http\Controllers\Admin\ExportController;
use Newnet\Cms\Http\Controllers\Admin\CrawlController;
use Newnet\Cms\Http\Controllers\Admin\CrawlHistoryController;
use Newnet\Cms\Http\Controllers\Admin\CrawlHistoryItemController;
use Newnet\Cms\Http\Controllers\Admin\KeywordController;
use Newnet\Cms\Http\Controllers\Admin\SatelliteController;
use Newnet\Cms\Http\Controllers\Admin\SatelliteSyncController;
use Newnet\Cms\Http\Controllers\Admin\FaqController;

Route::prefix('cms')
    ->name('cms.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('page', PageController::class);
        Route::resource('post', PostController::class);
        Route::resource('category', CategoryController::class);
        Route::resource('stories', StoryController::class);
        Route::resource('sync', SyncController::class);
        Route::resource('ratings', RatingController::class);
        Route::resource('content-list', ContentListController::class);
        Route::resource('crawl-history', CrawlHistoryController::class);
        Route::resource('keywords', KeywordController::class);
        Route::resource('satellite', SatelliteController::class);
        Route::resource('cms-faqs', FaqController::class);
    });

Route::prefix('cms/export')->group(function() {
    Route::get('/', [ExportController::class, 'index'])->name('cms.admin.export.index');
    Route::post('', [ExportController::class, 'export'])->name('cms.admin.export');
});

Route::prefix('cms/satellite-sync')->group(function() {
    Route::get('', [SatelliteSyncController::class, 'index'])->name('cms.admin.satellite-sync.index');
    Route::delete('{id}/delete', [SatelliteSyncController::class, 'destroy'])->name('cms.admin.satellite-sync.destroy');
    Route::post('', [SatelliteSyncController::class, 'sync'])->name('cms.admin.satellite-sync.sync');
});

Route::get('cms/settings', [CmsSettingController::class, 'index'])->name('cms.admin.setting.index');
Route::post('cms/crawl', [CrawlController::class, 'execute'])->name('cms.admin.crawl.execute');
Route::get('cms/crawl', [CrawlController::class, 'index'])->name('cms.admin.crawl.index');
Route::get('cms/crawl-settings', [CmsCrawlSettingController::class, 'index'])->name('cms.admin.crawl.setting');
Route::get('cms/crawl-history-item/{crawlHistoryId}/show', [CrawlHistoryItemController::class, 'index'])->name('cms.admin.crawl-history-item.index');
Route::get('cms/crawl-history-item/{id}/error', [CrawlHistoryItemController::class, 'error'])->name('cms.admin.crawl-history-item.error');
Route::get('cms/crawl-history-item/{id}/rerewrite', [CrawlHistoryItemController::class, 'reRewrite'])->name('cms.admin.crawl-history-item.reRewrite');

Route::delete('cms/posts/delete-multiple', [PostController::class, 'destroyMultipleItems']);
Route::delete('cms/categories/delete-multiple', [CategoryController::class, 'destroyMultipleItems']);
Route::delete('cms/stories/delete-multiple', [StoryController::class, 'destroyMultipleItems']);
Route::delete('cms/ratings/delete-multiple', [RatingController::class, 'destroyMultipleItems']);
Route::delete('cms/content-lists/delete-multiple', [ContentListController::class, 'destroyMultipleItems']);

Route::post('cms/ratings/publish', [RatingController::class, 'publish'])
        ->name('cms.admin.ratings.publish')
        ->middleware('admin.can:cms.admin.ratings.publish');

Route::prefix('stories')->group(function () {
    Route::prefix('')->group(function () {
        Route::get('{story_id}/builder', [StoryItemController::class, 'index'])
            ->name('cms.admin.story-item.index')
            ->middleware('admin.can:cms.admin.stories.edit');
    });

    Route::prefix('story-item')->middleware('admin.can:cms.admin.story.edit')->group(function () {
        Route::get('create', [StoryItemController::class, 'create'])
            ->name('cms.admin.story-item.create');

        Route::post('/', [StoryItemController::class, 'store'])
            ->name('cms.admin.story-item.store');

        Route::get('{id}/edit', [StoryItemController::class, 'edit'])
            ->name('cms.admin.story-item.edit');

        Route::put('{id}', [StoryItemController::class, 'update'])
            ->name('cms.admin.story-item.update');

        Route::delete('{id}', [StoryItemController::class, 'destroy'])
            ->name('cms.admin.story-item.destroy');
    });

    Route::prefix('setting')->middleware('admin.can:cms.admin.story.setting.edit')->group(function () {
        Route::get('/', [StorySettingController::class, 'index'])
            ->name('cms.admin.story.setting.index');

        Route::post('/', [StorySettingController::class, 'save'])
            ->name('cms.admin.story.setting.save');

    });
});

Route::prefix('cms/page')
    ->group(function () {
        Route::get('{id}/move-up', [PageController::class, 'moveUp'])
            ->name('cms.admin.page.move-up')
            ->middleware('admin.can:menu.page.edit');

        Route::get('{id}/move-down', [PageController::class, 'moveDown'])
            ->name('cms.admin.page.move-down')
            ->middleware('admin.can:menu.page.edit');
    });

Route::prefix('cms/category')
    ->group(function () {
        Route::get('{id}/move-up', [CategoryController::class, 'moveUp'])
            ->name('cms.admin.category.move-up')
            ->middleware('admin.can:menu.category.edit');

        Route::get('{id}/move-down', [CategoryController::class, 'moveDown'])
            ->name('cms.admin.category.move-down')
            ->middleware('admin.can:menu.category.edit');
    });

Route::prefix('comment')->group(function () {

    Route::post('{id}/reply', [CommentController::class, 'reply'])->name('cms.admin.comment.reply');

    Route::post('publish', [CommentController::class, 'publish'])
        ->name('cms.admin.comment.publish')
        ->middleware('admin.can:cms.admin.comment.publish');

    Route::delete('{id}/destroy', [CommentController::class, 'destroy'])
        ->name('cms.admin.comment.destroy')
        ->middleware('admin.can:cms.admin.comment.destroy');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])
    ->name('cms.admin.log-viewer')
    ->middleware('admin.can:cms.admin.log-viewer');

Route::get('documentation', [DocumentationController::class, 'index'])
    ->name('cms.admin.documentation.index')
    ->middleware('admin.can:cms.admin.log-viewer');


Route::get('documentation/{key}', [DocumentationController::class, 'detail'])
    ->name('cms.admin.documentation.detail')
    ->middleware('admin.can:cms.admin.log-viewer');

Route::get('/sync-tracking', [SyncController::class, 'trackingSyncProcess'])
    ->name('cms.admin.sync.trackingSyncProcess');

Route::delete('/sync-tracking/{id}/delete', [SyncController::class, 'deleteSyncProcess'])
    ->name('cms.admin.sync.deleteSyncProcess');

Route::post('/', [SyncController::class, 'save'])
    ->name('cms.admin.sync.save');
