<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Events\StorySettingEvent;
use Illuminate\Support\Facades\Process;

class StorySettingListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(StorySettingEvent $event)
  {
    $cmd = "php artisan cms:create-stories";
    Process::run($cmd);
  }
}
