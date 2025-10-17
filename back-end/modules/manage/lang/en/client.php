<?php
return [
    'model_name' => 'Client',

    'name'        => 'Name',
    'description' => 'Description',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',
    'avatar'      => 'Avatar',
    'image'       => 'Image logo',
    'stars'       => 'Stars',

    'index' => [
        'page_title'    => 'Client',
        'page_subtitle' => 'Client',
        'breadcrumb'    => 'Client',
    ],

    'create' => [
        'page_title'    => 'Add Client',
        'page_subtitle' => 'Add Client',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Client',
        'page_subtitle' => 'Edit Client',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Client successfully created!',
        'updated' => 'Client successfully updated!',
        'deleted' => 'Client successfully deleted!',
    ],
];
