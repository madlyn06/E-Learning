<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewItemEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var Model $item
   */
  public Model $item;

  public function __construct(Model $item)
  {
    $this->item = $item;
  }

  public function getItem()
  {
    return $this->item;
  }
}
