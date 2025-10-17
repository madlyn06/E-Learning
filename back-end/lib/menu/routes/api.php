<?php

use Illuminate\Support\Facades\Route;
use Newnet\Menu\Http\Controllers\Api\MenuController;

Route::prefix('menu')->group(function() {
  Route::get('{key}', [MenuController::class, 'getMenuItem'])->name('menu.api.menu.get-list');
});
