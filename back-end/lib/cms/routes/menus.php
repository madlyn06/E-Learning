<?php

use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('cms::module.module_name'), [
    'id' => CmsAdminMenuKey::CONTENT,
    'icon' => 'fas fa-newspaper',
    'order' => 4000,
]);

AdminMenu::addItem(__('cms::post.model_name'), [
    'id' => CmsAdminMenuKey::POST,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.post.index',
    'icon' => 'fas fa-pen-alt',
    'order' => 1,
]);

AdminMenu::addItem(__('cms::category.model_name'), [
    'id' => CmsAdminMenuKey::CATEGORY,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.category.index',
    'icon' => 'fas fa-folder-open',
    'order' => 2,
]);

AdminMenu::addItem(__('cms::page.model_name'), [
    'id' => CmsAdminMenuKey::PAGE,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.page.index',
    'icon' => 'fas fa-copy',
    'order' => 3,
]);

AdminMenu::addItem(__('cms::story.model_name'), [
    'id' => CmsAdminMenuKey::STORY,
    'parent' => CmsAdminMenuKey::CONTENT,
    'icon' => 'fas fa-copy',
    'order' => 4,
]);

AdminMenu::addItem(__('cms::story.list'), [
    'id' => CmsAdminMenuKey::STORY_ALL,
    'parent' => CmsAdminMenuKey::STORY,
    'route' => 'cms.admin.stories.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 1,
]);

AdminMenu::addItem(__('cms::story.setting.model_name'), [
    'id' => CmsAdminMenuKey::STORY_SETTING,
    'parent' => CmsAdminMenuKey::STORY,
    'route' => 'cms.admin.story.setting.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 2,
]);

AdminMenu::addItem(__('cms::sync.model_name'), [
    'id' => CmsAdminMenuKey::SYNCHRONIZATION,
    'parent' => CmsAdminMenuKey::CONTENT,
    'icon' => 'fas fa-copy',
    'order' => 4,
]);

AdminMenu::addItem(__('cms::sync.wp.model_name'), [
    'id' => CmsAdminMenuKey::SYNCHRONIZATION_SITE_WP,
    'parent' => CmsAdminMenuKey::SYNCHRONIZATION,
    'route' => 'cms.admin.sync.index',
    'icon' => 'fas fa-copy',
    'order' => 1,
]);

AdminMenu::addItem(__('cms::satellite.model_name'), [
    'id' => CmsAdminMenuKey::SYNCHRONIZATION_SITE,
    'parent' => CmsAdminMenuKey::SYNCHRONIZATION,
    'route' => 'cms.admin.satellite.index',
    'icon' => 'fas fa-copy',
    'order' => 1,
]);

AdminMenu::addItem(__('Đồng bộ'), [
    'id' => CmsAdminMenuKey::SYNCHRONIZATION_SITE_SYNC,
    'parent' => CmsAdminMenuKey::SYNCHRONIZATION,
    'route' => 'cms.admin.satellite-sync.index',
    'icon' => 'fas fa-copy',
    'order' => 1,
]);


AdminMenu::addItem(__('cms::setting.model_name'), [
    'id' => CmsAdminMenuKey::SETTING,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.setting.index',
    'icon' => 'fas fa-copy',
    'order' => 5,
]);

AdminMenu::addItem(__('Log Viewer'), [
    'id' => CmsAdminMenuKey::LOG_VIEWER,
    'parent' => SettingAdminMenuKey::SYSTEM,
    'route' => 'cms.admin.log-viewer',
    'icon' => 'fab fa-searchengin',
    'order' => 1000,
]);

AdminMenu::addItem(__('Documentation'), [
    'id' => CmsAdminMenuKey::DOCUMENTATION,
    'parent' => SettingAdminMenuKey::SYSTEM,
    'route' => 'cms.admin.documentation.index',
    'icon' => 'fab fa-searchengin',
    'order' => 1000,
]);

AdminMenu::addItem(__('cms::export.model_name'), [
    'id' => CmsAdminMenuKey::EXPORT,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.export.index',
    'icon' => 'fas fa-copy',
    'order' => 4,
]);

AdminMenu::addItem(__('cms::crawl.index.model_name'), [
    'id' => CmsAdminMenuKey::CRAWL,
    'parent' => CmsAdminMenuKey::CONTENT,
    'icon' => 'fas fa-copy',
    'order' => 5,
]);

AdminMenu::addItem(__('cms::crawl.create.model_name'), [
    'id' => CmsAdminMenuKey::CRAWL_CREATE,
    'parent' => CmsAdminMenuKey::CRAWL,
    'route' => 'cms.admin.crawl.index',
    'icon' => 'fas fa-copy',
    'order' => 2,
]);

AdminMenu::addItem(__('cms::crawl.history.model_name'), [
    'id' => CmsAdminMenuKey::CRAWL_HISTORY,
    'parent' => CmsAdminMenuKey::CRAWL,
    'route' => 'cms.admin.crawl-history.index',
    'icon' => 'fas fa-copy',
    'order' => 2,
]);

AdminMenu::addItem(__('cms::crawl.setting.model_name'), [
    'id' => CmsAdminMenuKey::CRAWL_SETTING,
    'parent' => CmsAdminMenuKey::CRAWL,
    'route' => 'cms.admin.crawl.setting',
    'icon' => 'fas fa-copy',
    'order' => 3,
]);

AdminMenu::addItem(__('cms::keyword.model_name'), [
    'id' => CmsAdminMenuKey::KEYWORD,
    'parent' => CmsAdminMenuKey::CONTENT,
    'route' => 'cms.admin.keywords.index',
    'icon' => 'fas fa-pen-alt',
    'order' => 5,
]);
