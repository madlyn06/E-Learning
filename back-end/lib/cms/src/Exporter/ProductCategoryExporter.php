<?php

namespace Newnet\Cms\Exporter;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Ecommerce\Models\Category;
use Newnet\Cms\Interface\ExporterInterface;
use Newnet\Cms\Utils\ConvertUtil;

class ProductCategoryExporter extends ExportAbstract implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  public function getColumns(): array
  {
    return ConvertUtil::getCategoryColumns();
  }

  public function getFileName(): string
  {
    $currentDate = date('Y-m-d_H:i:s');
    return "product_category_{$currentDate}.xlsx";
  }

  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    return Category::query();
  }

  public function map($catory): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      if ($column == 'parent_id') {
        $data[] = $catory->parent->name ?? null;
        continue;
      }
      if ($column === 'url') {
        $front_end_url = config('app.front_end_url').'/shop/danh-muc';
        $backend_url = config('app.url');
        $url = str_replace($backend_url, $front_end_url, $catory->url);
        $data[] = $url;
        continue;
      }
      $data[] = $catory->$column;
    }
    return $data;
  }
}
