<?php

use Illuminate\Support\Facades\Route;
use Newnet\Tag\Http\Controllers\Admin\TagController;

Route::prefix('tag')
    ->name('tag.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('tag', TagController::class);
    });

Route::get('tag/search', [TagController::class, 'search'])
    ->name('tag.admin.tag.search');

Route::delete('tags/delete-multiple', [TagController::class, 'destroyMultipleItems']);
