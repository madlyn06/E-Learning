<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Events\CrawledPostEvent;
use Newnet\Cms\Models\CrawlHistory;
use Newnet\Cms\Services\ContentService;

class CrawledPostListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(CrawledPostEvent $event)
  {
    /** @var CrawlHistory */
    $history = $event->getHistory();
    app(ContentService::class)->handleContent($history);
  }
}
