<?php

use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('seo::module.module_name'), [
    'id' => SeoAdminMenuKey::SEO,
    'parent' => SettingAdminMenuKey::SYSTEM,
    'icon' => 'fas fa-radar',
    'order' => 90,
]);

AdminMenu::addItem(__('seo::setting.model_name'), [
    'id' => SeoAdminMenuKey::SETTING,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.setting.index',
    'icon' => 'fal fa-cog',
    'order' => 1,
]);

// AdminMenu::addItem(__('seo::pre-redirect.model_name'), [
//     'id' => SeoAdminMenuKey::PRE_REDIRECT,
//     'parent' => SeoAdminMenuKey::SEO,
//     'route' => 'seo.admin.pre-redirect.index',
//     'icon' => 'fal fa-angle-double-right',
//     'order' => 2,
// ]);

AdminMenu::addItem(__('seo::error-redirect.model_name'), [
    'id' => SeoAdminMenuKey::ERROR_REDIRECT,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.error-redirect.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 3,
]);

AdminMenu::addItem(__('seo::ads.model_name'), [
    'id' => SeoAdminMenuKey::ADS,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.ads.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 4,
]);

AdminMenu::addItem(__('seo::short-link.model_name'), [
    'id' => SeoAdminMenuKey::SHORT_LINK,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.short-links.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 5,
]);

AdminMenu::addItem(__('seo::internal-link.model_name'), [
    'id' => SeoAdminMenuKey::SEO_INTERAL_LINK,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.internal-links.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 6,
]);
