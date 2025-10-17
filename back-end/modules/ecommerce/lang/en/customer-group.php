<?php
return [
    'model_name' => 'CustomerGroup',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'CustomerGroup',
        'page_subtitle' => 'CustomerGroup',
        'breadcrumb'    => 'CustomerGroup',
    ],

    'create' => [
        'page_title'    => 'Add CustomerGroup',
        'page_subtitle' => 'Add CustomerGroup',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit CustomerGroup',
        'page_subtitle' => 'Edit CustomerGroup',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'CustomerGroup successfully created!',
        'updated' => 'CustomerGroup successfully updated!',
        'deleted' => 'CustomerGroup successfully deleted!',
    ],
];
