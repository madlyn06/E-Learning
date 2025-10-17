<?php

use Newnet\Menu\MenuAdminMenuKey;
use Newnet\Theme\ThemeAdminMenuKey;

AdminMenu::addItem(__('menu::module.module_name'), [
    'id' => MenuAdminMenuKey::MENU,
    'parent' => ThemeAdminMenuKey::THEME,
    'route' => 'menu.admin.menu.index',
    'icon' => 'fas fa-bars',
    'order' => 10,
]);
