<?php

namespace Newnet\Setting\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Setting\Events\SettingEvent;

class SettingListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(SettingEvent $event)
  {
    $url = config('app.front_end_url').'/api/revalidate?type=setting';
    $response = Http::get($url);
    return $response->body();
  }
}
