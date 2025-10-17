<?php

namespace Newnet\Cms\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Newnet\Acl\Repositories\AdminRepositoryInterface;
use Newnet\Cms\Actions\CreateStoryFromPostAction;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Repositories\PostRepositoryInterface;

class SavedPostListener implements ShouldQueue
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
     // TODO: implement after
    return;
    if (!empty(setting('story_is_auto_create'))) {
      $post = app(PostRepositoryInterface::class)->getByConditions([
        'id' => $event->getPostId(),
        'is_created_story' => false,
      ])->first();

      if ($post) {
        $exceptCategory = setting('story_is_auto_create_from');
        $isAllowCreateStory = true;
        if (count($exceptCategory) > 0) {
          $categoriesOfPost = $post->categories->pluck('id')->toArray();
          foreach ($categoriesOfPost as $category) {
            if (in_array($category, $exceptCategory)) {
              $isAllowCreateStory = false;
              break;
            }
          }
        }
        if ($isAllowCreateStory) {
          $admin = app(AdminRepositoryInterface::class)->find($event->getAdminId());
          CreateStoryFromPostAction::createStory($post, $admin);
        }
      }
    }
  }
}
