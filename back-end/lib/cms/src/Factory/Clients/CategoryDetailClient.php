<?php

namespace Newnet\Cms\Factory\Clients;

use Newnet\Cms\Http\Resources\CategoryResource;
use Newnet\Cms\Http\Resources\PostResource;
use Newnet\Cms\Models\Category;

class CategoryDetailClient
{
  public static function getPostInCategory(Category $category)
  {
    $itemOnPage = setting('item_on_page', 15);
    $posts = $category->posts()->with(['author', 'categories', 'comments' => function ($query) {
      $query->where('is_published', 1);
    }])->where('is_active', 1)->orderBy('published_at', 'DESC')->paginate($itemOnPage);
    return [
      'posts' => PostResource::collection($posts)->response()->getData(true),
      'category' => new CategoryResource($category)
    ];
  }
}
