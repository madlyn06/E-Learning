<?php
return [
    'model_name' => 'Team',

    'name'        => 'Tiêu đề',
    'title'       => 'Chức danh',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'Team',
        'page_subtitle' => 'Team',
        'breadcrumb'    => 'Team',
    ],

    'create' => [
        'page_title'    => 'Thêm Team',
        'page_subtitle' => 'Thêm Team',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa Team',
        'page_subtitle' => 'Sửa Team',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo Team thành công!',
        'updated' => 'Cập nhật Team thành công!',
        'deleted' => 'Xoá Team thành công!',
    ],
];
