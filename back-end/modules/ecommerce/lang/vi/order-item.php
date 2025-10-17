<?php
return [
    'model_name' => 'OrderItem',

    'product_name' => 'Tên sản phẩm',
    'quantity' => 'Số lượng',
    'price' => 'Đơn giá',
    'total_price' => 'Thành tiền',
    'is_active'   => 'Kích hoạt',
    'created_at'  => 'Ngày tạo',

    'index' => [
        'page_title'    => 'OrderItem',
        'page_subtitle' => 'OrderItem',
        'breadcrumb'    => 'OrderItem',
    ],

    'create' => [
        'page_title'    => 'Thêm OrderItem',
        'page_subtitle' => 'Thêm OrderItem',
        'breadcrumb'    => 'Thêm',
    ],

    'edit' => [
        'page_title'    => 'Sửa OrderItem',
        'page_subtitle' => 'Sửa OrderItem',
        'breadcrumb'    => 'Sửa',
    ],

    'notification' => [
        'created' => 'Tạo OrderItem thành công!',
        'updated' => 'Cập nhật OrderItem thành công!',
        'deleted' => 'Xoá OrderItem thành công!',
    ],
];
