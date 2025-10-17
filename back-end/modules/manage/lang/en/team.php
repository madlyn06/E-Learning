<?php
return [
    'model_name' => 'Team',

    'name'        => 'Name',
    'title'       => 'Title',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Team',
        'page_subtitle' => 'Team',
        'breadcrumb'    => 'Team',
    ],

    'create' => [
        'page_title'    => 'Add Team',
        'page_subtitle' => 'Add Team',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Team',
        'page_subtitle' => 'Edit Team',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Team successfully created!',
        'updated' => 'Team successfully updated!',
        'deleted' => 'Team successfully deleted!',
    ],
];
