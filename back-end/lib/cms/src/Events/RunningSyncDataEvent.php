<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RunningSyncDataEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;
}
