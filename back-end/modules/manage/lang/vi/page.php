<?php
return [
    'model_name' => 'Trang',

    'name'        => 'Tiêu đề',
    'title'       => 'Chức danh',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'sort_order'  => 'Vị trí',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'Trang',
        'page_subtitle' => 'Trang',
        'breadcrumb'    => 'Trang',
    ],

    'create' => [
        'page_title'    => 'Thêm trang',
        'page_subtitle' => 'Thêm trang',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa trang',
        'page_subtitle' => 'Sửa trang',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo trang thành công!',
        'updated' => 'Cập nhật trang thành công!',
        'deleted' => 'Xoá trang thành công!',
    ],
];
