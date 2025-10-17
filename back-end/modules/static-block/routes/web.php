<?php

use Modules\StaticBlock\Http\Controllers\Web\StaticBlockController;
// ADD_WEB_USE_ROUTE_MODEL_HERE //

Route::get('staticblock/static-block/{id}', [StaticBlockController::class, 'detail'])->name('staticblock.web.static-block.detail');
// ADD_WEB_ROUTE_MODEL_HERE //
