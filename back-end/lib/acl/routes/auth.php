<?php

use Newnet\Acl\Http\Controllers\Auth\LoginController;
use Newnet\Acl\Http\Controllers\Auth\ForgotPasswordController;
use Newnet\Acl\Http\Controllers\Auth\ResetPasswordController;

Route::prefix(config('core.admin_prefix'))
    ->domain(config('core.admin_domain'))
    ->middleware(['web'])
    ->group(function () {
        Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('logout', [LoginController::class, 'logout']); // @Todo Remove logout GET method
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
    });
