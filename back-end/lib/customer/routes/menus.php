<?php

use Newnet\Customer\CustomerAdminMenuKey;

AdminMenu::addItem(__('customer::menu.customer.index'), [
    'id'         => CustomerAdminMenuKey::CUSTOMER,
    'route'      => 'customer.admin.customer.index',
    'icon'       => 'fas fa-users',
    'order'      => 9000,
]);
AdminMenu::addItem(__('customer::menu.customer.index'), [
    'id'         => CustomerAdminMenuKey::CUSTOMER_INDEX,
    'parent'     => CustomerAdminMenuKey::CUSTOMER,
    'route'      => 'customer.admin.customer.index',
    'icon'       => 'fas fa-users',
    'order'      => 1,
]);
AdminMenu::addItem(__('customer::menu.group.index'), [
    'id'         => CustomerAdminMenuKey::CUSTOMER_GROUP,
    'parent'     => CustomerAdminMenuKey::CUSTOMER,
    'route'      => 'customer.admin.group.index',
    'icon'       => 'fas fa-users-cog',
    'order'      => 2,
]);
