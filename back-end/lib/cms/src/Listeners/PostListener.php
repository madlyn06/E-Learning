<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Newnet\Cms\Actions\CreateStoryFromPostAction;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Models\Story;
use Newnet\Core\Utils\Common;

class PostListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(PostEvent $event)
  {
    $post = $event->post;
    if ($post->is_created_story) {
      $story = Story::wherePostId($post->id)->first();
      if (!$story) {
        CreateStoryFromPostAction::createStory($post);
      }
    }
  }
}
