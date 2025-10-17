<?php

use Modules\StaticBlock\StaticBlockAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Theme\ThemeAdminMenuKey;

AdminMenu::addItem(__('staticblock::static-block.model_name'), [
    'id' => StaticBlockAdminMenuKey::STATIC_BLOCK,
    'parent' => ThemeAdminMenuKey::THEME,
    'route'  => 'staticblock.admin.static-block.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);

// ADD_ADMIN_MENU_HERE //
