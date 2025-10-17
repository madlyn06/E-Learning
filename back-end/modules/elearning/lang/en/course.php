<?php
return [
    'model_name' => 'Course',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Course',
        'page_subtitle' => 'Course',
        'breadcrumb'    => 'Course',
    ],

    'create' => [
        'page_title'    => 'Add Course',
        'page_subtitle' => 'Add Course',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Course',
        'page_subtitle' => 'Edit Course',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Course successfully created!',
        'updated' => 'Course successfully updated!',
        'deleted' => 'Course successfully deleted!',
    ],
];
