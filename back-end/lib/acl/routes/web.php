<?php

use Illuminate\Support\Facades\Route;
use Newnet\Acl\Http\Controllers\Web\AdminController;

Route::get('acl/admin/{id}', [AdminController::class, 'detail'])->name('acl.web.admin.detail');
