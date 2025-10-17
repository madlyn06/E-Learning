<?php

namespace Newnet\Tag\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Exceptions\RevalidateException;
use Newnet\Tag\Events\TagEvent;

class TagListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(TagEvent $event)
  {
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($event->type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=tags';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate tags');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
