<?php

namespace Newnet\Acl\Facades;

use Illuminate\Support\Facades\Facade;
use Newnet\Acl\Contracts\PermissionManagerInterface;

class Permission extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PermissionManagerInterface::class;
    }
}
