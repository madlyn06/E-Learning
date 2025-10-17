<?php

use Newnet\Dashboard\DashboardAdminMenuKey;

AdminMenu::addItem(__('dashboard::message.menu_index'), [
    'id' => DashboardAdminMenuKey::DASHBOARD,
    'route' => 'admin.dashboard.index',
    'icon' => 'fas fa-home',
    'order' => 100,
    'public' => true,
]);
