<?php
return [
    'model_name' => 'Course',

    'name'        => 'Tiêu đề',
    'description' => 'Mô tả',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'Course',
        'page_subtitle' => 'Course',
        'breadcrumb'    => 'Course',
    ],

    'create' => [
        'page_title'    => 'Thêm Course',
        'page_subtitle' => 'Thêm Course',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa Course',
        'page_subtitle' => 'Sửa Course',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo Course thành công!',
        'updated' => 'Cập nhật Course thành công!',
        'deleted' => 'Xoá Course thành công!',
    ],
];
