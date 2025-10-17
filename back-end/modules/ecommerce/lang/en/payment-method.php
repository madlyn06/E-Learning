<?php
return [
  'model_name' => 'Payment method',

  'name'        => 'Tên phương thức',
  'description' => 'Mô tả',
  'code'     => 'Mã phương thức',
  'is_active'   => 'Kích hoạt',
  'created_at'  => 'Ngày tạo',
  'owner' => 'Tên chủ tài khoản',
  'branch' => 'Chi nhánh',
  'number' => 'Số tài khoản',

  'index' => [
    'page_title'    => 'Payment method',
    'page_subtitle' => 'Payment method',
    'breadcrumb'    => 'Payment method',
  ],

  'create' => [
    'page_title'    => 'Add payment method',
    'page_subtitle' => 'Add payment method',
    'breadcrumb'    => 'Add',
  ],

  'edit' => [
    'page_title'    => 'Edit payment method',
    'page_subtitle' => 'Edit payment method',
    'breadcrumb'    => 'Edit',
  ],

  'notification' => [
    'created' => 'Created payment method successfully!',
    'updated' => 'Updated nhật payment method successfully!',
    'deleted' => 'Deleted payment method successfully!',
  ],
];
