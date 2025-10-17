<?php

namespace Modules\Ecommerce\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Ecommerce\Models\Order;

class OrderEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var string $type
   */
  public string $order;

  public function __construct(Order $order)
  {
    $this->order = $order;
  }
}
