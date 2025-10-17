<?php

use Illuminate\Support\Facades\Route;
use Newnet\Tag\Http\Controllers\Api\TagController;

Route::prefix('tags')->group(function() {
  Route::get('', [TagController::class, 'getAllTags'])->name('tags.api.tag.index');
  Route::get('{slug}', [TagController::class, 'getTag'])->name('tags.api.tag.get-tag');
  Route::get('posts/{slug}', [TagController::class, 'getPostsInTag'])->name('tags.api.tag.posts-tag');
});
