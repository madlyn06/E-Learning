<?php
return [
    'model_name' => 'Product',

    'name'        => 'Name',
    'description' => 'Content',
    'content'     => 'Content',
    'is_active'   => 'Is Activated',
    'created_at'  => 'Created At',

    'index' => [
        'page_title'    => 'Product',
        'page_subtitle' => 'Product',
        'breadcrumb'    => 'Product',
    ],

    'create' => [
        'page_title'    => 'Add Product',
        'page_subtitle' => 'Add Product',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Product',
        'page_subtitle' => 'Edit Product',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Product successfully created!',
        'updated' => 'Product successfully updated!',
        'deleted' => 'Product successfully deleted!',
    ],
];
