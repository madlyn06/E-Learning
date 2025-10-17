<?php
return [
    'model_name' => 'FAQ',

    'name'        => 'Question name',
    'answer'      => 'Answer',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'FAQ',
        'page_subtitle' => 'FAQ',
        'breadcrumb'    => 'FAQ',
    ],

    'create' => [
        'page_title'    => 'Add FAQ',
        'page_subtitle' => 'Add FAQ',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit FAQ',
        'page_subtitle' => 'Edit FAQ',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'FAQ successfully created!',
        'updated' => 'FAQ successfully updated!',
        'deleted' => 'FAQ successfully deleted!',
    ],
];
