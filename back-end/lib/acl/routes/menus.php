<?php

use Newnet\Acl\AclAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('acl::module.module_name'), [
    'id' => AclAdminMenuKey::ACL,
    'parent' => get_admin_setting('enable_megamenu') ? SettingAdminMenuKey::SYSTEM : '',
    'icon' => 'fas fa-users-cog',
    'order' => get_admin_setting('enable_megamenu') ? 10 : 9600,
]);

AdminMenu::addItem(__('acl::user.model_name'), [
    'id' => AclAdminMenuKey::USER,
    'parent' => AclAdminMenuKey::ACL,
    'route' => 'acl.admin.user.index',
    'icon' => 'fas fa-users-cog',
    'order' => 1,
]);

AdminMenu::addItem(__('acl::role.model_name'), [
    'id' => AclAdminMenuKey::ROLE,
    'parent' => AclAdminMenuKey::ACL,
    'route' => 'acl.admin.role.index',
    'icon' => 'fas fa-user-tag',
    'order' => 2,
]);
