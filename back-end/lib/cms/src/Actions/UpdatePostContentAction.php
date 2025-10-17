<?php

namespace Newnet\Cms\Actions;

use Illuminate\Support\Facades\Http;
use Newnet\Cms\Exceptions\RevalidateException;
use Newnet\Cms\Models\Post;

class UpdatePostContentAction
{
  public static function updateContent($type, $data = [])
  {
    $oldKey = $data['oldKey'];
    $newKey = $data['newKey'];
    if ($oldKey !== $newKey) {
      $posts = Post::where('content', 'like', '%' . $oldKey . '%')->get();
      foreach ($posts as $post) {
        $post->update(['content' => str_replace($oldKey, $newKey, $post->content)]);
      }
    }
    self::fetch($type);
  }

  private static function fetch($type)
  {
    $url = config('app.front_end_url').'/api/revalidate';
    switch ($type) {
      case 'created':
      case 'updated':
      case 'deleted':
        $url .= '?type=story';
        break;
      default:
        throw new RevalidateException('Type not supported in revalidate story');
        break;
    }
    $response = Http::get($url);
    return $response->body();
  }
}
