<?php

namespace Newnet\Cms\Exporter;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Newnet\Cms\Interface\ExporterInterface;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Utils\ConvertUtil;

class AllExporter implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  public function getColumns(): array
  {
    return ConvertUtil::getPostColumns();
  }

  public function export(array $selectedColumns)
  {
    return Excel::download(new $this, 'active_users.xlsx');
  }

  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    // dd($selectedColumns);
    return Post::query();
  }

  public function headings(): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $name = $this->getColumns();
    $data = [];
    foreach ($columns as $column) {
      $data[] = $name[$column];
    }
    return $data;
  }

  public function map($post): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      $data[] = $post->$column;
    }
    return $data;
  }

  /**
   * Thiết lập kích thước mỗi chunk
   */
  public function chunkSize(): int
  {
    return 1000;
  }
}
