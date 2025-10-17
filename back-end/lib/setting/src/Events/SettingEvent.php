<?php

namespace Newnet\Setting\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingEvent
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
