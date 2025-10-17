<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Models\Story;

class StoryEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var $item
   */
  private $item;

  public function __construct($item)
  {
    $this->item = $item;
  }

  public function getItem()
  {
    return $this->item;
  }
}
