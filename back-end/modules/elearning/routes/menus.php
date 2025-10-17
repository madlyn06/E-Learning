<?php

use Modules\Elearning\ElearningAdminMenuKey;

AdminMenu::addItem(__('elearning::module.module_name'), [
    'id' => ElearningAdminMenuKey::ELEARNING,
    'icon' => 'fas fa-list',
    'order' => 1000,
]);

AdminMenu::addItem(__('elearning::course.model_name'), [
    'id'     => ElearningAdminMenuKey::COURSE,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.course.index',
    'icon'   => 'fas fa-cube',
    'order'  => 1,
]);
            
AdminMenu::addItem(__('elearning::section.model_name'), [
    'id'     => ElearningAdminMenuKey::SECTION,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.section.index',
    'icon'   => 'fas fa-cube',
    'order'  => 2,
]);
            
AdminMenu::addItem(__('elearning::lesson.model_name'), [
    'id'     => ElearningAdminMenuKey::LESSON,
    'parent' => ElearningAdminMenuKey::ELEARNING,
    'route'  => 'elearning.admin.lesson.index',
    'icon'   => 'fas fa-cube',
    'order'  => 3,
]);
            
// ADD_ADMIN_MENU_HERE //
