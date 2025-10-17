<?php

use Illuminate\Support\Facades\Route;
use Newnet\Cms\Http\Controllers\Web\PageController;
use Newnet\Cms\Http\Controllers\Web\PostController;
use Newnet\Cms\Http\Controllers\Web\CategoryController;
use Newnet\Cms\Http\Controllers\Web\StoryController;

Route::get('cms/page/{id}', [PageController::class, 'detail'])->name('cms.web.page.detail');
Route::get('cms/post/{id}', [PostController::class, 'detail'])->name('cms.web.post.detail');
Route::get('cms/category/{id}', [CategoryController::class, 'detail'])->name('cms.web.category.detail');
Route::get('cms/stories/{id}', [StoryController::class, 'detail'])->name('cms.web.stories.detail');
