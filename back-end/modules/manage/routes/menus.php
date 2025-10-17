<?php

use Modules\Manage\ManageAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;

AdminMenu::addItem(__('manage::module.module_name'), [
    'id' => ManageAdminMenuKey::MANAGE,
    'icon' => 'fas fa-list',
    'order' => 1000,
]);

AdminMenu::addItem(__('manage::service.model_name'), [
    'id'     => ManageAdminMenuKey::SERVICE,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.service.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);

AdminMenu::addItem(__('manage::portfolio.model_name'), [
    'id'     => ManageAdminMenuKey::PORTFOLIO,
    'parent' => ManageAdminMenuKey::MANAGE,
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);

AdminMenu::addItem(__('manage::portfolio-category.model_name'), [
    'id'     => ManageAdminMenuKey::PORTFOLIO_CATEGORY,
    'parent' => ManageAdminMenuKey::PORTFOLIO,
    'route'  => 'manage.admin.portfolio-categories.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);

AdminMenu::addItem(__('manage::portfolio-project.model_name'), [
    'id'     => ManageAdminMenuKey::PORTFOLIO_PROJECT,
    'parent' => ManageAdminMenuKey::PORTFOLIO,
    'route'  => 'manage.admin.portfolio-projects.index',
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);

AdminMenu::addItem(__('manage::brand.model_name'), [
    'id'     => ManageAdminMenuKey::BRAND,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.brand.index',
    'icon'   => 'fas fa-cube',
    'order'  => 3,
]);

AdminMenu::addItem(__('manage::document.model_name'), [
    'id'     => ManageAdminMenuKey::FILE,
    'parent' => ManageAdminMenuKey::MANAGE,
    'icon'   => 'fas fa-cube',
    'order'  => 3,
]);

AdminMenu::addItem(__('manage::document.file_category.model_name'), [
    'id'     => ManageAdminMenuKey::FILE_CATEGORY,
    'parent' => ManageAdminMenuKey::FILE,
    'route'  => 'manage.admin.file-categories.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);

AdminMenu::addItem(__('manage::document.all.model_name'), [
    'id'     => ManageAdminMenuKey::FILE_ALL,
    'parent' => ManageAdminMenuKey::FILE,
    'route'  => 'manage.admin.documents.index',
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);

AdminMenu::addItem(__('manage::faq.model_name'), [
    'id'     => ManageAdminMenuKey::FAQ,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.faq.index',
    'icon'   => 'fas fa-cube',
    'order'  => 4,
]);
            
AdminMenu::addItem(__('manage::client.model_name'), [
    'id'     => ManageAdminMenuKey::CLIENT,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.client.index',
    'icon'   => 'fas fa-cube',
    'order'  => 5,
]);

AdminMenu::addItem(__('manage::contact.model_name'), [
    'id'     => ManageAdminMenuKey::CONTACT,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.contact.index',
    'icon'   => 'fas fa-cube',
    'order'  => 6,
]);

AdminMenu::addItem(__('manage::newsletter.model_name'), [
    'id'     => ManageAdminMenuKey::NEWSLETTER,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.newsletter.index',
    'icon'   => 'fas fa-cube',
    'order'  => 7,
]);

AdminMenu::addItem(__('manage::seo.model_name'), [
    'id'     => ManageAdminMenuKey::SEO,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.seo.index',
    'icon'   => 'fas fa-cube',
    'order'  => 8,
]);

AdminMenu::addItem(__('manage::banner.model_name'), [
    'id'     => ManageAdminMenuKey::BANNER,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.banner.index',
    'icon'   => 'fas fa-cube',
    'order'  => 9,
]);

AdminMenu::addItem(__('manage::reason.model_name'), [
    'id'     => ManageAdminMenuKey::REASON,
    'parent' => ManageAdminMenuKey::MANAGE,
    'route'  => 'manage.admin.reason.index',
    'icon'   => 'fas fa-cube',
    'order'  => 10,
]);
// ADD_ADMIN_MENU_HERE //
