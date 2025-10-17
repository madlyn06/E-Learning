<?php

namespace Newnet\Tag\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Newnet\Cms\Http\Resources\PostResource;
use Newnet\Cms\Models\Post;
use Newnet\Core\Utils\Common;
use Newnet\Tag\Http\Resources\TagResource;
use Newnet\Tag\Models\Tag;

class TagController extends Controller
{
  /**
   * Get all tags
   */
  public function getAllTags()
  {
    return TagResource::collection(Tag::paginate(setting('item_on_page', 10)) ?? []);
  }

  public function getPostsInTag($slug)
  {
    $requestPath = Common::convertSlug($slug);
    $tag = Tag::whereSlug($requestPath)->first();
    if (!$tag) {
      return response()->json([
        'statusCode' => 404,
        'message' => 'Tag not found',
      ], Response::HTTP_BAD_REQUEST);
    }
    $postsInTag = $tag->entries(Post::class)->orderBy('id', 'DESC')->paginate(setting('item_on_page', 10));
    return [
      'tag' => new TagResource($tag),
      'posts' => PostResource::collection($postsInTag ?? [])->response()->getData(true)
    ];
  }

  public function getTag($slug)
  {
    $requestPath = Common::convertSlug($slug);
    return new TagResource(Tag::whereSlug($requestPath)->first());
  }
}
