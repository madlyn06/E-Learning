<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Events\DeletedStoryEvent;
use Newnet\Cms\Models\Post;

class DeletedStoryListener implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Handle the event.
   *
   * @param object $event
   * @return void
   */
  public function handle(DeletedStoryEvent $event)
  {
    $key = $event->getKey();
    $posts = Post::where('content', 'like', '%' . $key . '%')->get();
      foreach ($posts as $post) {
        $strKey = '[story_code="'.$key.'"]';
        $post->update(['content' => str_replace($strKey, '', $post->content), 'is_created_story' => false]);
      }
  }
}
