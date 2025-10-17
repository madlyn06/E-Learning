<?php

namespace Modules\Ecommerce\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Modules\Ecommerce\Events\OrderEvent;
use Modules\Manage\Events\ClientEvent;
use Modules\Manage\Exceptions\RevalidateException;

class OrderListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(OrderEvent $event)
  {
    $order = $event->order;
    // TODO Send mail notification
  }
}
