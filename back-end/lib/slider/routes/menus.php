<?php

AdminMenu::addItem(__('slider::menu.slider.index'), [
    'id'         => 'slider_root',
    'parent'     => 'cms',
    'route'      => 'slider.admin.slider.index',
    'permission' => 'slider.admin.slider.index',
    'icon'       => 'fas fa-layer-group',
    'order'      => 20,
]);
