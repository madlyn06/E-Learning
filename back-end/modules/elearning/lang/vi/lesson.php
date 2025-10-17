<?php
return [
    'model_name' => 'Lesson',

    'name'        => 'Tiêu đề',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'Lesson',
        'page_subtitle' => 'Lesson',
        'breadcrumb'    => 'Lesson',
    ],

    'create' => [
        'page_title'    => 'Thêm Lesson',
        'page_subtitle' => 'Thêm Lesson',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa Lesson',
        'page_subtitle' => 'Sửa Lesson',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo Lesson thành công!',
        'updated' => 'Cập nhật Lesson thành công!',
        'deleted' => 'Xoá Lesson thành công!',
    ],
];
