<?php

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Newnet\Cms\Http\Controllers\Api\CommentController;
use Newnet\Cms\Http\Controllers\Api\BlogController;
use Newnet\Cms\Http\Controllers\Api\RatingController;
use Newnet\Cms\Http\Controllers\Api\StoryController;
use Newnet\Cms\Http\Controllers\Api\CrawlController;
use Newnet\Cms\Http\Controllers\Api\KeywordController;
use Newnet\Cms\Http\Controllers\Api\RedirectController;
use Newnet\Cms\Http\Controllers\Api\SatelliteSyncController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([ApiKeyMiddleware::class, 'throttle:elearning-auth'])->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/categories', [BlogController::class, 'categories'])->name('cms.api.blog.categories');
    Route::get('/categories/{id}', [BlogController::class, 'categoryDetail'])->name('cms.api.blog.category-detail');
    Route::get('/categories/detail/{id}', [BlogController::class, 'getCategoryDetail'])->name('cms.api.blog.get-category-detail');
    Route::get('/all-categories', [BlogController::class, 'getAllCategories'])->name('cms.api.blog.getAllCategories');
    Route::get('/all-posts', [BlogController::class, 'getAllPosts'])->name('cms.api.blog.getAllPosts');
    Route::get('/posts', [BlogController::class, 'posts'])->name('cms.api.blog.posts');
    Route::get('/get-recent-posts', [BlogController::class, 'getRecentPosts'])->name('cms.api.blog.getRecentPosts');
    Route::get('/get-related-posts/{slug}', [BlogController::class, 'getRelatedPosts'])->name('cms.api.blog.getRelatedPosts');
    Route::get('/posts/{slug}', [BlogController::class, 'getPostBySlug'])->name('cms.api.blog.get-post');
    Route::get('/archives', [BlogController::class, 'getArchives'])->name('cms.api.blog.archives');
    Route::get('/cms/comments/{post_id}', [CommentController::class, 'getComments'])->name('cms.api.blog.get-comments');
    Route::post('/cms/comment', [CommentController::class, 'store'])->name('cms.api.blog.comment');
    Route::get('/cms/get-item/{slug}', [BlogController::class, 'getItemBySlug'])->name('cms.api.blog.getItemBySlug');
    Route::get('/pages', [BlogController::class, 'pages'])->name('cms.api.blog.pages');
    Route::get('/stories', [StoryController::class, 'getStories'])->name('cms.api.blog.stories');
    Route::get('/stories/{slug}', [StoryController::class, 'getStoryBySlug'])->name('cms.api.blog.get-story');
    Route::get('ratings', [RatingController::class, 'index'])->name('cms.api.rating.index');
    Route::post('ratings', [RatingController::class, 'store'])->name('cms.api.rating.store');
    Route::get('posts-in-category/{categorySlug}', [BlogController::class, 'getAllPostsInCategory'])->name('cms.api.blog.posts-in-category');
    Route::get('get-total-items/{type}', [BlogController::class, 'getTotalItems'])->name('cms.api.blog.get-total-items');
    Route::get('/keywords', [KeywordController::class, 'getKeywords'])->name('cms.api.keyword.get-keywords');

    // Sync data
    Route::post('satellite-sync', [SatelliteSyncController::class, 'sync'])->name('cms.api.satellite.sync');
    Route::get('redirect/check', [RedirectController::class, 'checkStatus'])->name('cms.api.redirect.check');
});

Route::get('/cms/crawl-status', [CrawlController::class, 'checkStatus'])->name('cms.api.crawl.status');
Route::get('/cms/get-crawl-history', [CrawlController::class, 'getCrawlHistory'])->name('cms.api.crawl.get-crawl-history');
