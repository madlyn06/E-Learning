<?php

namespace Newnet\Cms\Factory;

use Newnet\Cms\Exceptions\ExportException;

class ExporterFactory
{
  public static function create($type)
  {
    switch ($type) {
      case 'all':
        return new \Newnet\Cms\Exporter\AllExporter();
      case 'post':
        return new \Newnet\Cms\Exporter\PostExporter();
      case 'post-category':
        return new \Newnet\Cms\Exporter\PostCategoryExporter();
      case 'order':
        return new \Newnet\Cms\Exporter\OrderExporter();
      case 'download':
        return new \Newnet\Cms\Exporter\DownloadExporter();
      case 'product':
        return new \Newnet\Cms\Exporter\ProductExporter();
      case 'product-category':
        return new \Newnet\Cms\Exporter\ProductCategoryExporter();
      default:
        throw new ExportException('Exporter not found type: ' . $type);
    }
  }
}
