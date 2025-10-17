<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Actions\UpdatePostContentAction;
use Newnet\Cms\Events\StoryEvent;

class UpdatedStoryListener implements ShouldQueue
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
    if (!empty($event->getData())) {
      // Handle update the posts has content existed this story
      UpdatePostContentAction::updateContent($event->getType(), $event->getData());
    }
  }
}
