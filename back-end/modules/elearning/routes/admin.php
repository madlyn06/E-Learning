<?php

use Illuminate\Support\Facades\Route;

Route::prefix('elearning')
    ->name('elearning.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('courses', \Modules\Elearning\Http\Controllers\Admin\CourseController::class);
		Route::resource('sections', \Modules\Elearning\Http\Controllers\Admin\SectionController::class);
		Route::resource('lessons', \Modules\Elearning\Http\Controllers\Admin\LessonController::class);
		Route::resource('categories', \Modules\Elearning\Http\Controllers\Admin\CategoryController::class);
		Route::resource('coupons', \Modules\Elearning\Http\Controllers\Admin\CouponController::class);
		Route::resource('memberships', \Modules\Elearning\Http\Controllers\Admin\MembershipController::class);
		Route::resource('teachers', \Modules\Elearning\Http\Controllers\Admin\TeacherController::class);
		Route::resource('users', \Modules\Elearning\Http\Controllers\Admin\UserController::class);
		Route::resource('enrollments', \Modules\Elearning\Http\Controllers\Admin\EnrollmentController::class);
		Route::resource('payment-methods', \Modules\Elearning\Http\Controllers\Admin\PaymentMethodController::class);
		Route::resource('reviews', \Modules\Elearning\Http\Controllers\Admin\ReviewController::class);
		Route::resource('wishlists', \Modules\Elearning\Http\Controllers\Admin\WishlistController::class);
		Route::resource('notes', \Modules\Elearning\Http\Controllers\Admin\NoteController::class);
		Route::resource('comments', \Modules\Elearning\Http\Controllers\Admin\CommentController::class);
    });

Route::prefix('elearning/payment-methods')
    ->group(function () {
        Route::get('{id}/config', [\Modules\Elearning\Http\Controllers\Admin\PaymentMethodController::class, 'config'])
            ->name('elearning.admin.payment-methods.config')
            ->middleware('admin.can:elearning.admin.payment_method.edit');
        Route::post('{id}/config', [\Modules\Elearning\Http\Controllers\Admin\PaymentMethodController::class, 'configUpdate'])
            ->name('elearning.admin.payment-methods.config.update')
            ->middleware('admin.can:elearning.admin.payment_method.edit');
    });

Route::prefix('elearning/category')
    ->group(function () {
        Route::get('{id}/move-up', [Modules\Elearning\Http\Controllers\Admin\CategoryController::class, 'moveUp'])
            ->name('elearning.admin.categories.move-up')
            ->middleware('admin.can:menu.category.edit');

        Route::get('{id}/move-down', [Modules\Elearning\Http\Controllers\Admin\CategoryController::class, 'moveDown'])
            ->name('elearning.admin.categories.move-down')
            ->middleware('admin.can:menu.category.edit');
    });

Route::prefix('elearning')
    ->middleware('admin.acl')
    ->group(function () {
        Route::prefix('reports')->group(function () {
            Route::get('/', [\Modules\Elearning\Http\Controllers\Admin\ReportController::class, 'index'])->name('elearning.admin.reports.index');
            Route::get('/revenue', [\Modules\Elearning\Http\Controllers\Admin\ReportController::class, 'revenue'])->name('elearning.admin.reports.revenue');
            Route::get('/enrollments', [\Modules\Elearning\Http\Controllers\Admin\ReportController::class, 'enrollments'])->name('elearning.admin.reports.enrollments');
            Route::get('/user-activity', [\Modules\Elearning\Http\Controllers\Admin\ReportController::class, 'userActivity'])->name('elearning.admin.reports.user-activity');
            Route::get('/course-performance', [\Modules\Elearning\Http\Controllers\Admin\ReportController::class, 'coursePerformance'])->name('elearning.admin.reports.course-performance');
        });
        Route::get('settings', [\Modules\Elearning\Http\Controllers\Admin\SettingController::class, 'index'])->name('elearning.admin.settings.index');
    });
