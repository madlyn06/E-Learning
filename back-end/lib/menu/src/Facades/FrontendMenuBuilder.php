<?php

namespace Newnet\Menu\Facades;

use Illuminate\Support\Facades\Facade;

class FrontendMenuBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'frontend-menu-builder';
    }
}
