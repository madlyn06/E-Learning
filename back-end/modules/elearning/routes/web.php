<?php

use Modules\Elearning\Http\Controllers\Web\CategoryController;
use Modules\Elearning\Http\Controllers\Web\CourseController;
use Modules\Elearning\Http\Controllers\Web\SectionController;
use Modules\Elearning\Http\Controllers\Web\LessonController;
use Modules\Elearning\Http\Controllers\Web\PaymentController;

// ADD_WEB_USE_ROUTE_MODEL_HERE //
Route::get('elearning/category/{id}', [CategoryController::class, 'detail'])->name('elearning.web.category.detail');
Route::get('elearning/course/{id}', [CourseController::class, 'detail'])->name('elearning.web.course.detail');
Route::get('elearning/section/{id}', [SectionController::class, 'detail'])->name('elearning.web.section.detail');
Route::get('elearning/lesson/{id}', [LessonController::class, 'detail'])->name('elearning.web.lesson.detail');
// ADD_WEB_ROUTE_MODEL_HERE //

// Payment callbacks
Route::get('elearning/payments/callback/{gateway}', [PaymentController::class, 'callback'])->name('elearning.web.payment.callback');
Route::get('elearning/payments/success/{gateway}', [PaymentController::class, 'handleCallback'])->name('elearning.web.payment.success');
Route::get('elearning/payments/cancel/{gateway}', [PaymentController::class, 'handleCallback'])->name('elearning.web.payment.cancel');