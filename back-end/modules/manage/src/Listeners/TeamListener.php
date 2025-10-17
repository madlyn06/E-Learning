<?php

namespace Modules\Manage\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Modules\Manage\Events\TeamEvent;
use Modules\Manage\Exceptions\RevalidateException;

class TeamListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(TeamEvent $event)
  {
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($event->type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=member';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate member');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
