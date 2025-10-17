<?php
return [
    'model_name' => 'Contact',

    'name'        => 'Name / Email / Phone',
    'content'     => 'Content',
    'subject'     => 'Subject',
    'status'      => 'Status',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Contact',
        'page_subtitle' => 'Contact',
        'breadcrumb'    => 'Contact',
    ],

    'create' => [
        'page_title'    => 'Add Contact',
        'page_subtitle' => 'Add Contact',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Contact',
        'page_subtitle' => 'Edit Contact',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Contact successfully created!',
        'updated' => 'Contact successfully updated!',
        'deleted' => 'Contact successfully deleted!',
    ],
];
