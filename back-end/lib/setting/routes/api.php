<?php

use Illuminate\Support\Facades\Route;
use Newnet\Setting\Http\Controllers\Api\SettingController;

Route::get('configs', [SettingController::class, 'getConfigs'])->name('setting.api.setting.getConfigs');
Route::get('configs/{keys}', [SettingController::class, 'getConfigsByKey'])->name('setting.api.setting.getConfigsByKey');
