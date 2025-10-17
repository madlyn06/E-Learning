<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Events\CategoryEvent;
use Newnet\Cms\Exceptions\RevalidateException;

class CategoryListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(CategoryEvent $event)
  {
    $type = $event->type;
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=category';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate category');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
