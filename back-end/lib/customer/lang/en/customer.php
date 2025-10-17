<?php
return [
    'model_name' => 'Customer',
    'name'                  => 'Name',
    'group'                 => 'Group',
    'created_at'            => 'Created At',
    'description'           => 'Description',
    'content'               => 'Content',
    'is_active'             => 'Enable',
    'phone'                 => 'Phone',
    'email'                 => 'Email',
    'avatar'                => 'Avatar',
    'street'                => 'Street',
    'password'              => 'Password',
    'password_confirmation' => 'Confirm Password',

    'gender' => [
        'label'  => 'Gender',
        'male'   => 'Male',
        'female' => 'Female',
    ],

    'index' => [
        'page_title'    => 'Customer',
        'page_subtitle' => 'Customer',
        'breadcrumb'    => 'Customer',
    ],

    'create' => [
        'page_title'    => 'Add Customer',
        'page_subtitle' => 'Add Customer',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Customer',
        'page_subtitle' => 'Edit Customer',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Customer successfully created!',
        'updated' => 'Customer successfully updated!',
        'deleted' => 'Customer successfully deleted!',
    ],

    'login' => [
        'meta_title' => 'Login',
    ],

    'profile' => [
        'head'       => 'Customer Profile',
        'meta_title' => 'Profile',
        'update'     => 'Update',
    ],

    'tabs' => [
        'information' => 'Customer Information',
        'address'     => 'Address',
        'extension'   => 'Extension Field',
    ],

    'delete' => 'Delete Selected',
];
