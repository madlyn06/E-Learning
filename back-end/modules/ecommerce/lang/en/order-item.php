<?php
return [
    'model_name' => 'OrderItem',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'OrderItem',
        'page_subtitle' => 'OrderItem',
        'breadcrumb'    => 'OrderItem',
    ],

    'create' => [
        'page_title'    => 'Add OrderItem',
        'page_subtitle' => 'Add OrderItem',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit OrderItem',
        'page_subtitle' => 'Edit OrderItem',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'OrderItem successfully created!',
        'updated' => 'OrderItem successfully updated!',
        'deleted' => 'OrderItem successfully deleted!',
    ],
];
