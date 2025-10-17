<?php

namespace Modules\Manage\Strategy;

class FileProcessor
{
  protected $handler;

  public function process($file)
  {
    $this->handler = FileHandlerFactory::createHandler($file);
    $this->handler->preview($file);
  }
}
