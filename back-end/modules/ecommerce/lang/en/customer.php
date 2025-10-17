<?php
return [
    'model_name' => 'Customer',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Customer',
        'page_subtitle' => 'Customer',
        'breadcrumb'    => 'Customer',
    ],

    'create' => [
        'page_title'    => 'Add Customer',
        'page_subtitle' => 'Add Customer',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Customer',
        'page_subtitle' => 'Edit Customer',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Customer successfully created!',
        'updated' => 'Customer successfully updated!',
        'deleted' => 'Customer successfully deleted!',
    ],
];
