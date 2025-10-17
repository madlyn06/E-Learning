<?php

use Newnet\Media\MediaAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('media::module.module_name'), [
    'id' => MediaAdminMenuKey::MEDIA,
    'parent' => SettingAdminMenuKey::SYSTEM,
    'route' => 'media.admin.media.index',
    'icon' => 'fas fa-photo-video',
    'order' => 5,
]);

AdminMenu::addItem(__('media::module.module_name_list'), [
    'id' => MediaAdminMenuKey::MEDIA_LIST,
    'parent' => MediaAdminMenuKey::MEDIA,
    'route' => 'media.admin.media.index',
    'icon' => 'fas fa-photo-video',
    'order' => 1,
]);

AdminMenu::addItem(__('media::setting.module_name'), [
    'id' => MediaAdminMenuKey::MEDIA_SETTING,
    'parent' => MediaAdminMenuKey::MEDIA,
    'route' => 'media.admin.settings.index',
    'icon' => 'fas fa-photo-video',
    'order' => 2,
]);
