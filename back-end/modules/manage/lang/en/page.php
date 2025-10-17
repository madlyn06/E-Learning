<?php
return [
    'model_name' => 'Page',

    'name'        => 'Name',
    'title'       => 'Title',
    'description' => 'Description',
    'content'     => 'Content',
    'sort_order'  => 'Sort order',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Page',
        'page_subtitle' => 'Page',
        'breadcrumb'    => 'Page',
    ],

    'create' => [
        'page_title'    => 'Add Page',
        'page_subtitle' => 'Add Page',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Page',
        'page_subtitle' => 'Edit Page',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Page successfully created!',
        'updated' => 'Page successfully updated!',
        'deleted' => 'Page successfully deleted!',
    ],
];
