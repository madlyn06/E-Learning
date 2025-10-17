<?php
return [
    'model_name' => 'Lesson',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Lesson',
        'page_subtitle' => 'Lesson',
        'breadcrumb'    => 'Lesson',
    ],

    'create' => [
        'page_title'    => 'Add Lesson',
        'page_subtitle' => 'Add Lesson',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Lesson',
        'page_subtitle' => 'Edit Lesson',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Lesson successfully created!',
        'updated' => 'Lesson successfully updated!',
        'deleted' => 'Lesson successfully deleted!',
    ],
];
