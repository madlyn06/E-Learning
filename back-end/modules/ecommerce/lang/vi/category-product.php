<?php
return [
    'model_name' => 'CategoryProduct',

    'name'        => 'Tiêu đề',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'CategoryProduct',
        'page_subtitle' => 'CategoryProduct',
        'breadcrumb'    => 'CategoryProduct',
    ],

    'create' => [
        'page_title'    => 'Thêm CategoryProduct',
        'page_subtitle' => 'Thêm CategoryProduct',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa CategoryProduct',
        'page_subtitle' => 'Sửa CategoryProduct',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo CategoryProduct thành công!',
        'updated' => 'Cập nhật CategoryProduct thành công!',
        'deleted' => 'Xoá CategoryProduct thành công!',
    ],
];
