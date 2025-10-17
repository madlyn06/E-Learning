<?php

namespace Modules\StaticBlock\Facade;

use Illuminate\Support\Facades\Facade;

class StaticBlockRender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'static.block';
    }
}
