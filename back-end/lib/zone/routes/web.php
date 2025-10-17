<?php

use Newnet\Zone\Http\Controllers\Web\ZoneApiController;

Route::prefix('api/zone')->group(function () {
    Route::get('provinces', [ZoneApiController::class, 'provinces']);
    Route::get('provinces/{id}/districts', [ZoneApiController::class, 'districts']);
    Route::get('districts/{id}/townships', [ZoneApiController::class, 'townships']);
});
