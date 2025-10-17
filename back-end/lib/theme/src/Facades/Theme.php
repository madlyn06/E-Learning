<?php

namespace Newnet\Theme\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Newnet\Theme\ThemeManager
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'theme.manager';
    }
}
