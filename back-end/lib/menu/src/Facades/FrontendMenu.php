<?php

namespace Newnet\Menu\Facades;

use Illuminate\Support\Facades\Facade;

class FrontendMenu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'frontend-menu';
    }
}
