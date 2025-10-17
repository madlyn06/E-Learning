<?php

use Illuminate\Support\Facades\Route;
use Modules\Elearning\Http\Controllers\Web\AuthController;
// use Modules\Elearning\Http\Controllers\Web\__MODEL_CLASS_NAME__Controller;

// Route::prefix('api/__MODEL_SLUG_NAME__/__MODEL_SLUG_NAME__')->group(function () {
//     Route::get('', [__MODEL_CLASS_NAME__Controller::class, 'index']);
// });

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
