<?php

namespace Newnet\Slider\Facades;

use Illuminate\Support\Facades\Facade;

class SliderRender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'slider.render';
    }
}
