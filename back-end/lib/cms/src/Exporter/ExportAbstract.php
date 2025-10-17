<?php

namespace Newnet\Cms\Exporter;

use Maatwebsite\Excel\Facades\Excel;

abstract class ExportAbstract
{
  abstract public function getFileName(): string;
  abstract public function getColumns(): array;
  abstract public function query();

  public function export()
  {
    return Excel::download(new $this, $this->getFileName());
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

  public function chunkSize(): int
  {
    return 1000;
  }
}
