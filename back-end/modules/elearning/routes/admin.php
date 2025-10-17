<?php

Route::prefix('elearning')
    ->name('elearning.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('course', \Modules\Elearning\Http\Controllers\Admin\CourseController::class);
		Route::resource('section', \Modules\Elearning\Http\Controllers\Admin\SectionController::class);
		Route::resource('lesson', \Modules\Elearning\Http\Controllers\Admin\LessonController::class);
		// ADD_ROUTE_MODEL_HERE //
    });
