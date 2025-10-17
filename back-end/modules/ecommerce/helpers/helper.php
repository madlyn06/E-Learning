<?php

use Modules\Ecommerce\Enums\OrderStatus;
use Modules\Ecommerce\Enums\PaymentStatus;
use Modules\Ecommerce\Models\Category;
use Modules\Ecommerce\Models\Product;

if (!function_exists('get_category_products_parent_options'))
{
  function get_category_products_parent_options()
  {
    $options = [];

    $categoryTreeList = Category::defaultOrder()->withDepth()->get()->toFlatTree();
    foreach ($categoryTreeList as $item) {
      $options[] = [
        'value' => $item->id,
        'label' => trim(str_pad('', $item->depth * 3, '-')) . ' ' . $item->name,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_discount_type_options'))
{
  function get_discount_type_options()
  {
    $options = [];

    $discountTypes = [
      'amount' => 'Số tiền',
      'percent' => 'Phần trăm'
    ];
    foreach ($discountTypes as $key => $item) {
      $options[] = [
        'value' => $key,
        'label' => $item,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_products_options'))
{
  function get_products_options()
  {
    $options = [];

    $products = Product::all();
    foreach ($products as $item) {
      $options[] = [
        'value' => $item->id,
        'label' => $item->name,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_payment_status_options'))
{
  function get_payment_status_options()
  {
    $options = [];
    $paymentStatus = [
      PaymentStatus::PAID,
      PaymentStatus::UNPAID,
    ];
    foreach ($paymentStatus as $item) {
      $options[] = [
        'value' => $item,
        'label' => $item,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_order_status_options'))
{
  function get_order_status_options()
  {
    $options = [];

    $products = [
      OrderStatus::STATUS_PENDING,
      OrderStatus::STATUS_APPROVAL,
      OrderStatus::STATUS_SHIPING,
      OrderStatus::STATUS_SHIPPED,
      OrderStatus::STATUS_COMPLETED,
    ];
    foreach ($products as $item) {
      $options[] = [
        'value' => $item,
        'label' => $item,
      ];
    }

    return $options;
  }
}

if (!function_exists('format_money'))
{
  function format_money($amount): string
  {
    return number_format($amount, 0, ',', '.');
  }
}
