<?php

namespace Newnet\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Newnet extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Newnet\Core\Newnet::class;
    }
}
