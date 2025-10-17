<?php

AdminMenu::addItem(__('contact::menu.contact.index'), [
    'id'         => 'contact_root',
    'route'      => 'contact.admin.contact.index',
    'permission' => 'contact.admin.contact.index',
    'icon'       => 'fas fa-id-card',
    'order'      => 1000,
]);
AdminMenu::addItem(__('contact::menu.contact.index'), [
    'id'         => 'contact_index',
    'parent'    =>  'contact_root',
    'route'      => 'contact.admin.contact.index',
    'permission' => 'contact.admin.contact.index',
    'icon'       => 'fas fa-phone',
    'order'      => 1,
]);
AdminMenu::addItem(__('contact::menu.newsletter.index'), [
    'id'         => 'newsletter_index',
    'parent'    =>  'contact_root',
    'route'      => 'contact.admin.newsletter.index',
    'permission' => 'contact.admin.newsletter.index',
    'icon'       => 'fas fa-mail-bulk',
    'order'      => 2,
]);

AdminMenu::addItem(__('contact::menu.label.index'), [
    'id'         => 'contact_label',
    'parent'    =>  'contact_root',
    'route'      => 'contact.admin.label.index',
    'permission' => 'contact.admin.label.index',
    'icon'       => 'fas fa-tags',
    'order'      => 3,
]);
