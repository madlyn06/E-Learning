<?php
return [
    'model_name' => 'Category',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Category',
        'page_subtitle' => 'Category',
        'breadcrumb'    => 'Category',
    ],

    'create' => [
        'page_title'    => 'Add Category',
        'page_subtitle' => 'Add Category',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Category',
        'page_subtitle' => 'Edit Category',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Category successfully created!',
        'updated' => 'Category successfully updated!',
        'deleted' => 'Category successfully deleted!',
    ],
];
