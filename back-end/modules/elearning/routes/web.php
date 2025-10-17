<?php

use Modules\Elearning\Http\Controllers\Web\CourseController;
use Modules\Elearning\Http\Controllers\Web\SectionController;
use Modules\Elearning\Http\Controllers\Web\LessonController;
// ADD_WEB_USE_ROUTE_MODEL_HERE //

Route::get('elearning/course/{id}', [CourseController::class, 'detail'])->name('elearning.web.course.detail');
Route::get('elearning/section/{id}', [SectionController::class, 'detail'])->name('elearning.web.section.detail');
Route::get('elearning/lesson/{id}', [LessonController::class, 'detail'])->name('elearning.web.lesson.detail');
// ADD_WEB_ROUTE_MODEL_HERE //
