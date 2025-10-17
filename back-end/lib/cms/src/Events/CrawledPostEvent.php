<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Models\CrawlHistory;

class CrawledPostEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var string $type
   */
  private CrawlHistory $history;

  public function __construct(CrawlHistory $history)
  {
    $this->history = $history;
  }

  public function getHistory(): CrawlHistory
  {
    return $this->history;
  }
}
