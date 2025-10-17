<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Actions\HandleContentListableAction;
use Newnet\Cms\Events\ContentListableEvent;
class ContentListableListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(ContentListableEvent $event)
  {
    $post = $event->getPost();
    HandleContentListableAction::action($post);
  }
}
