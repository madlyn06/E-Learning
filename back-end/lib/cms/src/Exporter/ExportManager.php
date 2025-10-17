<?php

namespace Newnet\Cms\Exporter;

use InvalidArgumentException;
use Newnet\Cms\Interface\ExporterInterface;

class ExportManager
{
  protected $strategies = [];

  public function registerStrategy(string $type, ExporterInterface $strategy)
  {
    $this->strategies[$type] = $strategy;
  }

  public function getColumns(string $type): array
  {
    if (!isset($this->strategies[$type])) {
      throw new InvalidArgumentException("Export type [$type] is not supported.");
    }

    return $this->strategies[$type]->getColumns();
  }

  public function export(string $type, array $selectedColumns)
  {
    if (!isset($this->strategies[$type])) {
      throw new InvalidArgumentException("Export type [$type] is not supported.");
    }

    return $this->strategies[$type]->export($selectedColumns);
  }
}
