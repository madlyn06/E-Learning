<?php

use Newnet\Zone\ZoneAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('zone::menu.zone.index'), [
    'id'         => ZoneAdminMenuKey::ZONE,
    'parent'     => SettingAdminMenuKey::SYSTEM,
    'route'      => 'zone.admin.province.index',
    'icon'       => 'typcn typcn-sort-numerically-outline',
    'order'      => 7,
]);
