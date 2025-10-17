<?php
return [
    'model_name' => 'Order',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Order',
        'page_subtitle' => 'Order',
        'breadcrumb'    => 'Order',
    ],

    'create' => [
        'page_title'    => 'Add Order',
        'page_subtitle' => 'Add Order',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Order',
        'page_subtitle' => 'Edit Order',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Order successfully created!',
        'updated' => 'Order successfully updated!',
        'deleted' => 'Order successfully deleted!',
    ],
];
