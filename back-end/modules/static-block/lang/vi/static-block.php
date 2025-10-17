<?php
return [
    'model_name' => 'StaticBlock',

    'name'        => 'Tiêu đề',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',
    'css'   => 'CSS',
    'script' => 'Script',
    'image' => 'Image',
    'key' => 'Key',

    'index' => [
        'page_title'    => 'StaticBlock',
        'page_subtitle' => 'StaticBlock',
        'breadcrumb'    => 'StaticBlock',
    ],

    'create' => [
        'page_title'    => 'Thêm StaticBlock',
        'page_subtitle' => 'Thêm StaticBlock',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa StaticBlock',
        'page_subtitle' => 'Sửa StaticBlock',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo StaticBlock thành công!',
        'updated' => 'Cập nhật StaticBlock thành công!',
        'deleted' => 'Xoá StaticBlock thành công!',
    ],
];
