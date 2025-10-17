<?php

use Modules\Ecommerce\EcommerceAdminMenuKey;

AdminMenu::addItem(__('ecommerce::module.module_name'), [
    'id' => EcommerceAdminMenuKey::ECOMMERCE,
    'icon' => 'fas fa-list',
    'order' => 1000,
]);

AdminMenu::addItem(__('ecommerce::category.model_name'), [
    'id'     => EcommerceAdminMenuKey::CATEGORY,
    'parent' => EcommerceAdminMenuKey::ECOMMERCE,
    'route'  => 'ecommerce.admin.category.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);

AdminMenu::addItem(__('ecommerce::product.model_name'), [
    'id'     => EcommerceAdminMenuKey::PRODUCT,
    'parent' => EcommerceAdminMenuKey::ECOMMERCE,
    'route'  => 'ecommerce.admin.product.index',
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);

AdminMenu::addItem(__('ecommerce::order.model_name'), [
    'id'     => EcommerceAdminMenuKey::ORDER,
    'parent' => EcommerceAdminMenuKey::ECOMMERCE,
    'route'  => 'ecommerce.admin.order.index',
    'icon'   => 'fas fa-cube',
    'order'  => 3,
]);

AdminMenu::addItem(__('ecommerce::payment-method.model_name'), [
    'id'     => EcommerceAdminMenuKey::PAYMENT_METHOD,
    'parent' => EcommerceAdminMenuKey::ECOMMERCE,
    'route'  => 'ecommerce.admin.payment-methods.index',
    'icon'   => 'fas fa-cube',
    'order'  => 4,
]);

AdminMenu::addItem(__('ecommerce::discount.model_name'), [
    'id'     => EcommerceAdminMenuKey::DISCOUNT,
    'parent' => EcommerceAdminMenuKey::ECOMMERCE,
    'route'  => 'ecommerce.admin.discounts.index',
    'icon'   => 'fas fa-cube',
    'order'  => 5,
]);
