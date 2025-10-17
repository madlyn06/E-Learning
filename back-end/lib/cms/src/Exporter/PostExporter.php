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

class PostExporter extends ExportAbstract implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  public function getColumns(): array
  {
    return ConvertUtil::getPostColumns();
  }

  public function getFileName(): string
  {
    $currentDate = date('Y-m-d_H:i:s');
    return "post_{$currentDate}.xlsx";
  }

  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    return Post::query();
  }

  public function map($post): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      if ($column === 'categories') {
        $data[] = $post->categories ? $post->categories->pluck('name')->implode(', ') : null;
        continue;
      }
      if ($column === 'tags') {
        $data[] = $post->tags->pluck('name')->implode(', ');
        continue;
      }
      if ($column === 'author') {
        $data[] = $post->author->name;
        continue;
      }
      if ($column === 'url') {
        $front_end_url = config('app.front_end_url');
        $backend_url = config('app.url');
        $url = str_replace($backend_url, $front_end_url, $post->url);
        $data[] = $url;
        continue;
      }
      $data[] = $post->$column;
    }
    return $data;
  }
}
