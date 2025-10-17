<?php

namespace Newnet\Cms\Facades;

use Illuminate\Support\Facades\Facade;

class PageLayout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'module.cms.page-layout';
    }
}
