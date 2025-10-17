<?php
return [
    'model_name' => 'Post',

    'name'        => 'Title',
    'slug'        => 'Slug',
    'created_at'  => 'Created At',
    'description' => 'Description',
    'content'     => 'Content',
    'is_active'   => 'Active',
    'is_sticky'   => 'Sticky',
    'sort_order'  => 'Sort Order',
    'author'      => 'Author',
    'category'    => 'Category',
    'image'       => 'Image',
    'is_viewed'    => 'Total Views',
    'is_created_story' => 'Is created story?',
    'append_internal_link' => 'Append Internal Link',

    'index' => [
        'page_title'    => 'Post',
        'page_subtitle' => 'Post',
        'breadcrumb'    => 'Post',
    ],

    'create' => [
        'page_title'    => 'Add Post',
        'page_subtitle' => 'Add Post',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Post',
        'page_subtitle' => 'Edit Post',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Post successfully created!',
        'updated' => 'Post successfully updated!',
        'deleted' => 'Post successfully deleted!',
    ],

    'tabs' => [
        'info'    => 'Information',
        'seo'     => 'SEO',
        'comment' => 'Comment',
        'rating'  => 'Rating',
        'list'    => 'Table of content',
    ],
];
