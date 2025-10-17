<?php

namespace Newnet\Core\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Console\Command;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewnetInstalled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
}
