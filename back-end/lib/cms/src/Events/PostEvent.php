<?php

namespace Newnet\Cms\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Newnet\Cms\Models\Post;

class PostEvent
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  /**
   * @var Post $post
   */
  public Post $post;

  public function __construct(Post $post)
  {
    $this->post = $post;
  }

  public function getPost()
  {
    return $this->post;
  }
}
