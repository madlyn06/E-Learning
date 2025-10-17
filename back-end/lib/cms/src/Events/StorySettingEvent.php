<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StorySettingEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var string $type
   */
  public string $type;

  /**
   * @var $adminId
   */
  public $adminId;

  public function __construct(string $type, $adminId = null)
  {
    $this->type = $type;
    $this->adminId = $adminId;
  }

  public function getAdminId()
  {
    return $this->adminId;
  }
}
