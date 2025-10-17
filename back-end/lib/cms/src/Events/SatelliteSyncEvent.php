<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SatelliteSyncEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $satelliteSite;

  public $satelliteSyncId;

  public function __construct($satelliteSite, $satelliteSyncId)
  {
    $this->satelliteSite = $satelliteSite;
    $this->satelliteSyncId = $satelliteSyncId;
  }
}
