<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeletedStoryEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var string $type
   */
  private string $key;

  public function __construct(string $key)
  {
    $this->key = $key;
  }

  public function getKey()
  {
    return $this->key;
  }
}
