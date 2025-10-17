<?php

use Newnet\Setting\SettingAdminMenuKey;
use Newnet\Theme\ThemeAdminMenuKey;

AdminMenu::addItem(__('theme::module.module_name'), [
    'id' => ThemeAdminMenuKey::THEME,
    'parent' => get_admin_setting('enable_megamenu') ? SettingAdminMenuKey::SYSTEM : '',
    'icon' => 'fas fa-palette',
    'order' => get_admin_setting('enable_megamenu') ? 2 : 9500,
]);

AdminMenu::addItem(__('theme::theme-setting.model_name'), [
    'id' => ThemeAdminMenuKey::SETTING,
    'parent' => ThemeAdminMenuKey::THEME,
    'route' => 'theme.admin.theme-setting.index',
    'icon' => 'fas fa-cog',
    'order' => 1,
]);

AdminMenu::addItem(__('theme::theme-manager.model_name'), [
    'id' => ThemeAdminMenuKey::MANAGER,
    'parent' => ThemeAdminMenuKey::THEME,
    'route' => 'theme.admin.theme-manager.index',
    'icon' => 'fas fa-palette',
    'order' => 2,
]);
