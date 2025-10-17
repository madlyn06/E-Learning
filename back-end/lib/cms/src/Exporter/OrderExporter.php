<?php

namespace Newnet\Cms\Exporter;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Ecommerce\Models\Order;
use Newnet\Cms\Interface\ExporterInterface;
use Newnet\Cms\Utils\ConvertUtil;

class OrderExporter extends ExportAbstract implements ExporterInterface, FromQuery, WithHeadings, WithMapping, WithChunkReading, ShouldQueue
{
  public function getColumns(): array
  {
    return ConvertUtil::getOrderColumns();
  }

  public function getFileName(): string
  {
    $currentDate = date('Y-m-d_H:i:s');
    return "order_{$currentDate}.xlsx";
  }

  public function query()
  {
    // $columns = array_intersect_key($this->getColumns(), array_flip($selectedColumns));
    return Order::query();
  }

  public function map($post): array
  {
    $columns = request()->columns;
    $columns = array_keys($columns);
    $data = [];
    foreach ($columns as $column) {
      if ($column === 'payment') {
        $data[] = $post->payment->name;
        continue;
      }
      if ($column === 'orderItems') {
        $data[] = $post->orderItems->map(function ($item) {
          return [
            'product_name' => $item->product->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'total_price' => $item->total_price,
          ];
        });
        continue;
      }
      $data[] = $post->$column;
    }
    return $data;
  }
}
