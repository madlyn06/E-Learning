<?php

namespace Newnet\Cms\Factory\Clients;

use Newnet\Cms\Http\Resources\KeywordResource;
use Newnet\Cms\Http\Resources\PostDetailResource;
use Newnet\Cms\Http\Resources\PostRelatedResource;
use Newnet\Cms\Http\Resources\PostTagResource;
use Newnet\Cms\Models\Post;
use Newnet\Core\Utils\Common;
use Newnet\Tag\Http\Resources\TagResource;

class PostDetailClient
{
  public static function getPostDetail(Post $post)
  {
    $catIds = $post->categories->pluck('id');
    $relatedPosts = null;
    if ($catIds->count() > 0) {
      $relatedPosts = Post::whereHas('categories', function ($query) use ($catIds) {
        $query->whereIn('id', $catIds);
      })->where('id', '!=', $post->id)->inRandomOrder()->limit(2)->get();
    }
    $comments = $post->comments()->where('is_published', true);
    return [
      'post' => new PostDetailResource($post),
      'comments' => [
        'counts' => $comments->count(),
        'items' => Common::buildCommentTree($comments->get())
      ],
      'related_posts' => $relatedPosts ? PostRelatedResource::collection($relatedPosts ?? []) : [],
      'tags' => TagResource::collection($post->tags ?? []),
      'keywords' => KeywordResource::collection($post->keywords ?? []),
      'posts_tag' => PostTagResource::collection($post->relatedPostsTag() ?? []),
    ];
  }
}
