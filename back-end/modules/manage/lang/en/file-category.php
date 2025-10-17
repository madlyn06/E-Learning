<?php
return [
    'model_name' => 'Category',

    'name'        => 'Title',
    'doc_type'    => 'Category type',
    'slug'        => 'Slug',
    'description' => 'Description',
    'content'     => 'Content',
    'is_active'   => 'Active',
    'created_at'  => 'Created At',
    'parent'      => 'Parent',
    'image'       => 'Image',
    'icon'        => 'Icon',

    'index' => [
        'page_title'    => 'File Category',
        'page_subtitle' => 'File Category',
        'breadcrumb'    => 'File Category',
    ],

    'create' => [
        'page_title'    => 'Add File Category',
        'page_subtitle' => 'Add File Category',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit File Category',
        'page_subtitle' => 'Edit File Category',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'File category successfully created!',
        'updated' => 'File Category successfully updated!',
        'deleted' => 'File category successfully deleted!',
    ],

    'tabs' => [
        'info'      => 'Information',
        'seo'       => 'SEO',
    ],
];
