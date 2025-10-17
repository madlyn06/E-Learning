<?php
return [
    'model_name' => 'Section',

    'name'        => 'Tiêu đề',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'Section',
        'page_subtitle' => 'Section',
        'breadcrumb'    => 'Section',
    ],

    'create' => [
        'page_title'    => 'Thêm Section',
        'page_subtitle' => 'Thêm Section',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa Section',
        'page_subtitle' => 'Sửa Section',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo Section thành công!',
        'updated' => 'Cập nhật Section thành công!',
        'deleted' => 'Xoá Section thành công!',
    ],
];
