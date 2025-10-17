<?php

use Modules\Manage\Models\FileCategory;
use Modules\Manage\Models\PortfolioCategory;
use Modules\Manage\Models\Service;
use Newnet\Seo\Models\Ads;

if (!function_exists('get_file_category_parent_options')) {
  /**
   * Get Category Parent Options
   *
   * @return array
   */
  function get_file_category_parent_options()
  {
    $options = [];

    $categoryTreeList = FileCategory::defaultOrder()->withDepth()->get()->toFlatTree();
    foreach ($categoryTreeList as $item) {
      $options[] = [
        'value' => $item->id,
        'label' => trim(str_pad('', $item->depth * 3, '-')) . ' ' . $item->name,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_service_options'))
{
  function get_service_options()
  {
    $options = [];

    $services = Service::whereIsActive(true)->get();
    foreach ($services as $item) {
      $options[] = [
        'value' => $item->id,
        'label' => $item->name,
      ];
    }

    return $options;
  }
}


if (!function_exists('get_portfolio_category_parent_options')) {
  /**
   * Get Category Parent Options
   *
   * @return array
   */
  function get_portfolio_category_parent_options()
  {
    $options = [];

    $categoryTreeList = PortfolioCategory::whereIsActive(true)->get();
    foreach ($categoryTreeList as $item) {
      $options[] = [
        'value' => $item->id,
        'label' => trim(str_pad('', $item->depth * 3, '-')) . ' ' . $item->name,
      ];
    }

    return $options;
  }
}

if (!function_exists('get_ads_id_by_code'))
{
  function get_ads_id_by_code($code)
  {
    $ads = Ads::where('code', $code)->first();
    return $ads ? $ads->id : null;
  }
}