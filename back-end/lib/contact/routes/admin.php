<?php

use Newnet\Contact\Http\Controllers\Admin\ContactController;
use Newnet\Contact\Http\Controllers\Admin\LabelController;
use Newnet\Contact\Http\Controllers\Admin\NewsletterController;
Route::prefix('contact')->group(function () {
    Route::prefix('contact')->group(function () {
        Route::get('', [ContactController::class, 'index'])
            ->name('contact.admin.contact.index')
            ->middleware('admin.can:contact.admin.contact.index');

        Route::get('create', [ContactController::class, 'create'])
            ->name('contact.admin.contact.create')
            ->middleware('admin.can:contact.admin.contact.create');

        Route::post('/', [ContactController::class, 'store'])
            ->name('contact.admin.contact.store')
            ->middleware('admin.can:contact.admin.contact.create');

        Route::get('{id}/edit', [ContactController::class, 'edit'])
            ->name('contact.admin.contact.edit')
            ->middleware('admin.can:contact.admin.contact.edit');

        Route::put('{id}', [ContactController::class, 'update'])
            ->name('contact.admin.contact.update')
            ->middleware('admin.can:contact.admin.contact.edit');

        Route::delete('{id}', [ContactController::class, 'destroy'])
            ->name('contact.admin.contact.destroy')
            ->middleware('admin.can:contact.admin.contact.destroy');
    });

    Route::prefix('label')->group(function () {
        Route::get('', [LabelController::class, 'index'])
            ->name('contact.admin.label.index')
            ->middleware('admin.can:contact.admin.label.index');

        Route::get('create', [LabelController::class, 'create'])
            ->name('contact.admin.label.create')
            ->middleware('admin.can:contact.admin.label.create');

        Route::post('/', [LabelController::class, 'store'])
            ->name('contact.admin.label.store')
            ->middleware('admin.can:contact.admin.label.create');

        Route::get('{id}/edit', [LabelController::class, 'edit'])
            ->name('contact.admin.label.edit')
            ->middleware('admin.can:contact.admin.label.edit');

        Route::put('{id}', [LabelController::class, 'update'])
            ->name('contact.admin.label.update')
            ->middleware('admin.can:contact.admin.label.edit');

        Route::delete('{id}', [LabelController::class, 'destroy'])
            ->name('contact.admin.label.destroy')
            ->middleware('admin.can:contact.admin.label.destroy');
    });

    Route::prefix('newsletter')->group(function () {
        Route::get('', [NewsletterController::class, 'index'])
            ->name('contact.admin.newsletter.index')
            ->middleware('admin.can:contact.admin.newsletter.index');
    });
});
