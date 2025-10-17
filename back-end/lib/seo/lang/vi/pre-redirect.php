<?php
return [
    'model_name' => 'Chuyển hướng sớm',

    'from_path'   => 'URL cần redirect',
    'to_url'      => 'URL đích',
    'status_code' => 'Mã',
    'created_at'  => 'Ngày tạo',

    'function_not_enable' => 'Tính năng này chưa được kích hoạt.',

    'index' => [
        'page_title'    => 'Chuyển hướng sớm',
        'page_subtitle' => 'Chuyển hướng sớm',
        'breadcrumb'    => 'Chuyển hướng sớm',
    ],

    'create' => [
        'page_title'    => 'Tạo link chuyển hướng',
        'page_subtitle' => 'Tạo link chuyển hướng',
        'breadcrumb'    => 'Tạo',
    ],

    'edit' => [
        'page_title'    => 'Sửa link chuyển hướng',
        'page_subtitle' => 'Sửa link chuyển hướng',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo thành công!',
        'updated' => 'Cập nhật thành công!',
        'deleted' => 'Xoá thành công!',
    ],

    'status_codes' => [
        '301' => '301 (Chuyển hướng vĩnh viễn)',
        '302' => '302 (Chuyển hướng tạm thời)',
    ],
];
