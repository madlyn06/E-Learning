<?php
return [
    'model_name'  => 'Story',
    'list'        => 'Tất cả stories',
    'name'        => 'Tên Story',
    'post_id'     => 'Bài viết',
    'slug'        => 'Short code',
    'description' => 'Mô tả',
    'is_active'   => 'Kích hoạt?',
    'layout'      => 'Layout',
    'created_at'  => 'Ngày tạo',
    'image'       => 'Hình ảnh',

    'layouts' => [
        'owlcarousel2' => 'Owl Carousel 2',
    ],

    'index' => [
        'page_title'    => 'Story',
        'page_subtitle' => 'Story',
        'breadcrumb'    => 'Story',
    ],

    'create' => [
        'page_title'    => 'Thêm Story',
        'page_subtitle' => 'Thêm Story',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa Story',
        'page_subtitle' => 'Sửa Story',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo mới Story thành công!',
        'updated' => 'Cập nhật Story thành công!',
        'deleted' => 'Xóa Story thành công!',
    ],

    'setting' => [
        'model_name'  => 'Cài đặt',
        'story_image_min' => 'Số lượng ảnh tối thiểu trong story',
        'story_image_max' => 'Số lượng ảnh tối đa trong story',
        'story_image_transfer' => 'Chuyển ảnh sau (giây)',
        'story_text_link' => 'Text link',
        'story_text_point' => 'Trỏ tới',
        'story_insert_to' => 'Chèn story vào bài viết',
        'story_is_auto_create' => 'Tự động tạo story khi bài viết được tạo hoặc cập nhật (với bài viết chưa có story)',
        'story_is_auto_create_from' => 'Tạo story từ tất cả bài viết ngoại trừ trong danh mục',
        'story_is_auto_delete' => 'Xoá story khi bài viết bị xoá',
        'story_is_draft' => 'Story được tạo ra là bản nháp',
        'story_btn_create_stories' => 'Tạo stories cho những bài viết chưa có story',

        'pointer_home' => 'Trang chủ',
        'pointer_post' => 'Bài Viết',
        'insert' => [
            'first' => 'Đầu bài viết',
            'before_h2' => 'Trước thẻ h2 đầu tiên',
            'last' => 'Cuối bài viết',
        ],

        'index' => [
            'page_title'    => 'Story Setting',
            'page_subtitle' => 'Story Setting',
            'breadcrumb'    => 'Story Setting',
        ],

        'notification' => [
            'updated' => 'Updated story setting successfully updated!',
        ],
    ]
];
