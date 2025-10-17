<?php

namespace Newnet\Cms\Exporter;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Manage\Models\File;
use Newnet\Cms\Interface\ExporterInterface;
use Newnet\Cms\Utils\ConvertUtil;

class DownloadExporter extends ExportAbstract implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  /**
   * Inheritance from ExportAbstract
   */
  public function getColumns(): array
  {
    return ConvertUtil::getDownloadColumns();
  }

  /**
   * Inheritance from ExportAbstract
   */
  public function getFileName(): string
  {
    $currentDate = date('Y-m-d_H:i:s');
    return "download_{$currentDate}.xlsx";
  }

  /**
   * Inheritance from ExportAbstract
   */
  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    return File::query();
  }

  public function map($download): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      if ($column === 'categories') {
        $data[] = $download->categories ? $download->categories->pluck('name')->implode(', ') : null;
        continue;
      }
      if ($column === 'url') {
        $front_end_url = config('app.front_end_url').'/download';
        $backend_url = config('app.url');
        $url = str_replace($backend_url, $front_end_url, $download->url);
        $data[] = $url;
        continue;
      }
      $data[] = $download->$column;
    }
    return $data;
  }
}
