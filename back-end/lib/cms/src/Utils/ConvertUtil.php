<?php

namespace Newnet\Cms\Utils;

class ConvertUtil
{
  public static function getPostColumns()
  {
    return [
      'id' => 'ID',
      'name' => 'Title',
      'categories' => 'Category',
      'url' => 'URL',
      'author' => 'Author',
      'tags' => 'Tags',
      'content' => 'Content',
      'description'=> 'Description',
      'created_at' => 'Created At',
      'published_at' => 'Published At',
    ];
  }

  public static function getCategoryColumns()
  {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'parent_id' => 'Parent',
      'description' => 'Description',
      'content' => 'Content',
      'url' => 'URL',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  public static function getPageColumns()
  {
    return [
      'id' => 'ID',
      'name' => 'Name',
      'url' => 'URL',
      'content' => 'Content',
      'description' => 'Description',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    ];
  }

  public static function getProductColumns()
  {
    return [
      'id' => 'ID',
      'name' => 'Tên sản phẩm',
      'categories' => 'Danh mục',
      'origin_price' => 'Giá',
      'sale_price' => 'Giá khuyến mãi',
      'description' => 'Mô tả',
      'content' => 'Nội dung',
      'url' => 'URL',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
    ];
  }

  public static function getOrderColumns()
  {
    return [
      'id' => 'ID',
      'order_no' => 'Mã đơn hàng',
      'email' => 'Email',
      'orderItems' => 'Chi tiết sản phẩm',
      'total_price' => 'Tổng tiền',
      'discount_code' => 'Mã giảm giá',
      'discount_amount' => 'Số tiền giảm',
      'shipping_address' => 'Địa chỉ giao hàng',
      'note' => 'Ghi chú',
      'payment' => 'Phương thức thanh toán',
      'payment_status' => 'Trạng thái thanh toán',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
    ];
  }

  public static function getDownloadColumns()
  {
    return [
      'id' => 'ID',
      'name' => 'Tên file',
      'categories' => 'Danh mục',
      'company_name' => 'Tên công ty',
      'file_version' => 'Phiên bản',
      'file_size' => 'Kích thước',
      'download_count' => 'Số lần tải',
      'download_url' => 'URL tải',
      'required' => 'Yêu cầu',
      'published_date' => 'Ngày xuất bản',
      'description' => 'Mô tả',
      'content' => 'Nội dung',
      'url' => 'URL',
      'created_at' => 'Ngày tạo',
      'updated_at' => 'Ngày cập nhật',
    ];
  }
}
