<?php
return [
    'model_name'  => 'Story',
    'list'        => 'All stories',
    'name'        => 'Story Name',
    'slug'        => 'Short code',
    'description' => 'Description',
    'is_active'   => 'Is Active',
    'layout'      => 'Layout',
    'created_at'  => 'Created At',
    'image'       => 'Image',

    'layouts' => [
        'owlcarousel2' => 'Owl Carousel 2',
    ],

    'index' => [
        'page_title'    => 'Story',
        'page_subtitle' => 'Story',
        'breadcrumb'    => 'Story',
    ],

    'create' => [
        'page_title'    => 'Add Story',
        'page_subtitle' => 'Add Story',
        'breadcrumb'    => 'Add',
    ],

    'edit' => [
        'page_title'    => 'Edit Story',
        'page_subtitle' => 'Edit Story',
        'breadcrumb'    => 'Edit',
    ],

    'notification' => [
        'created' => 'Story successfully created!',
        'updated' => 'Story successfully updated!',
        'deleted' => 'Story successfully deleted!',
    ],

    'setting' => [
        'model_name'  => 'Setting',
        'story_image_min' => 'Min images in story',
        'story_image_max' => 'Max images in story',
        'story_image_transfer' => 'Transfer next image after (s)',
        'story_text_link' => 'Text link',
        'story_text_point' => 'Text point',
        'story_insert_to' => 'The position of the story insert to post',
        'story_is_auto_create' => 'Auto create the story when post is created or updated (with post without story)',
        'story_is_auto_create_from' => 'Auto create story from all posts except the category',
        'story_is_auto_delete' => 'Delete the story when post is deleted',
        'story_is_draft' => 'Story is draft after creating',
        'story_btn_create_stories' => 'Create a new story from posts without story',

        'pointer_home' => 'Home page point',
        'pointer_post' => 'Post point',
        'insert' => [
            'first' => 'The first of post',
            'before_h2' => 'Before first h2 element',
            'last' => 'The last of post',
        ],

        'index' => [
            'page_title'    => 'Story Setting',
            'page_subtitle' => 'Story Setting',
            'breadcrumb'    => 'Story Setting',
        ],

        'notification' => [
            'updated' => 'Updated story setting successfully updated!',
        ],
    ],
];
