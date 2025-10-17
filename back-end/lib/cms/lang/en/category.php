<?php
return [
    'model_name' => 'Category',

    'name'        => 'Title',
    'slug'        => 'Slug',
    'description' => 'Description',
    'content'     => 'Content',
    'is_active'   => 'Active',
    'created_at'  => 'Created At',
    'parent'      => 'Parent',
    'image'       => 'Image',
    'icon'        => 'Icon',

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

    'tabs' => [
        'info'      => 'Information',
        'seo'       => 'SEO',
    ],
];
