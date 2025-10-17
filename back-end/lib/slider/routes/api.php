<?php

use Illuminate\Support\Facades\Route;
use Newnet\Slider\Http\Controllers\Api\SliderController;

Route::prefix('sliders')->group(function() {
  Route::get('{sliderKey}', [SliderController::class, 'getSliderItemsActivated'])->name('slider.api.slider.get-items');
});
