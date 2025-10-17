<?php

namespace Newnet\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SettingBuilder
 *
 * @package Newnet\Core\Facades
 *
 * @method static \Newnet\Core\Services\AdminSetting\SettingBuilderGroup add($className)
 * @method static array getPanels()
 * @method static void save()
 */
class SettingBuilder extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'newnet.setting-builder';
    }
}
