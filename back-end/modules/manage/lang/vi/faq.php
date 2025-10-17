<?php
return [
    'model_name' => 'FAQ',

    'name'        => 'Câu hỏi',
    'answer'      => 'Trả lời',
    'content'     => 'Nội dung',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'FAQ',
        'page_subtitle' => 'FAQ',
        'breadcrumb'    => 'FAQ',
    ],

    'create' => [
        'page_title'    => 'Thêm FAQ',
        'page_subtitle' => 'Thêm FAQ',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa FAQ',
        'page_subtitle' => 'Sửa FAQ',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo FAQ thành công!',
        'updated' => 'Cập nhật FAQ thành công!',
        'deleted' => 'Xoá FAQ thành công!',
    ],
];
