<?php

namespace Newnet\Cms\Exporter;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Ecommerce\Models\Product;
use Newnet\Cms\Interface\ExporterInterface;
use Newnet\Cms\Utils\ConvertUtil;

class ProductExporter extends ExportAbstract implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  public function getColumns(): array
  {
    return ConvertUtil::getProductColumns();
  }

  public function getFileName(): string
  {
    $currentDate = date('Y-m-d_H:i:s');
    return "product_{$currentDate}.xlsx";
  }

  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    return Product::query();
  }

  public function map($product): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      if ($column === 'categories') {
        $data[] = $product->categories ? $product->categories->pluck('name')->implode(', ') : null;
        continue;
      }
      if ($column === 'url') {
        $front_end_url = config('app.front_end_url').'/shop';
        $backend_url = config('app.url');
        $url = str_replace($backend_url, $front_end_url, $product->url);
        $data[] = $url;
        continue;
      }
      $data[] = $product->$column;
    }
    return $data;
  }
}
