<?php
return [
    'model_name' => 'Subscribe',

    'name'        => 'Name',
    'email'       => 'Email',
    'status'      => 'Status',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Newsletter',
        'page_subtitle' => 'Newsletter',
        'breadcrumb'    => 'Newsletter',
    ],

    'create' => [
        'page_title'    => 'Add Newsletter',
        'page_subtitle' => 'Add Newsletter',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Newsletter',
        'page_subtitle' => 'Edit Newsletter',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Newsletter successfully created!',
        'updated' => 'Newsletter successfully updated!',
        'deleted' => 'Newsletter successfully deleted!',
    ],
];
