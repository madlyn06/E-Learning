<?php

namespace Newnet\Slider\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Modules\Manage\Exceptions\RevalidateException;
use Newnet\Slider\Events\SliderEvent;

class SliderListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(SliderEvent $event)
  {
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($event->type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=slider';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate slider');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
