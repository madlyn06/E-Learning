<?php

namespace Newnet\Slider\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SliderEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var string $type
   */
  public string $type;

  public function __construct(string $type)
  {
    $this->type = $type;
  }
}
