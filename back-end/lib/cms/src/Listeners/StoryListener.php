<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Core\Utils\Common;

class StoryListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(StoryEvent $event)
  {
    $url = config('app.front_end_url') . '/api/revalidate';
    if ($event->getItem() && $event->getItem()->url) {
      $response = Http::post($url, [
        'slug' => Common::buildSlug($event->getItem()->url),
        'secret' => config('app.front_end_secret'),
        'type' => 'story',
      ]);
      return $response->body();
    }
  }
}
