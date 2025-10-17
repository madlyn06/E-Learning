<?php

namespace Newnet\Cms\Services;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Newnet\Cms\Http\Resources\CategoryDetailResource;
use Newnet\Cms\Http\Resources\CategoryResource;
use Newnet\Cms\Http\Resources\PostDetailResource;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Post;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Models\ErrorRedirect;
use Newnet\Seo\Models\Url;

class BlogService
{
  /**
   * Get list categories
   * @return array Category
   */
  public function getCategories()
  {
    $limit = request('limit');
    if ($limit) {
      $categories = Category::with(['posts' => function ($query) {
        $query->where('is_active', 1);
      }])->where('is_active', 1)->defaultOrder()->paginate($limit);
      return CategoryResource::collection($categories ?? []);
    }
    $categoriesCached = Cache::get('categories');
    if (!$categoriesCached) {
      $categories = Category::with(['posts' => function ($query) {
        $query->where('is_active', 1);
      }])->where('is_active', 1)->defaultOrder()->get();
      Cache::put('categories', $categories);
    } else {
      $categories = $categoriesCached;
    }
    return $categories;
  }

  /**
   * Get category detail
   * @param string|number
   * @return Category
   */
  public function getCategoryDetail($id)
  {
    if (is_numeric($id)) {
      $category = Category::findOrFail($id);
      return new CategoryDetailResource($category);
    }
    $slug = Common::convertSlug($id);
    $url = Url::where(['urlable_type' => 'Newnet\Cms\Models\Category', 'request_path' => $slug])->first();
    if (!$url) {
      return response()->json([
        'message' => 'Could not find category'
      ]);
    }
    return Category::findOrFail($url->urlable_id);
  }

  /**
   * Get list posts
   * @param Request $request
   * @return array
   */
  public function getPosts(Request $request)
  {
    $keyword = $request->get('q');
    $itemOnPage = setting('item_on_page', 15);
    if ($keyword) {
      $posts = Post::with(['author', 'categories', 'comments' => function ($query) {
        $query->where('is_published', 1);
      }])->where('is_active', true)
        ->where(function ($query) use ($keyword) {
          $query->where('name', 'LIKE', '%' . $keyword . '%');
        })->orderBy('id', 'DESC')->paginate($itemOnPage);
    } else {
      $posts = Post::with(['author', 'categories', 'comments' => function ($query) {
        $query->where('is_published', 1);
      }])->where('is_active', true)->orderBy('id', 'DESC')->paginate($itemOnPage);
    }
    return $posts;
  }

  /**
   * Get post detail from slug
   * @param string $slug
   * @return Post
   */
  public function getPostBySlug($slug)
  {
    if (is_numeric($slug)) {
      $post = Post::findOrFail($slug);
      return new PostDetailResource($post);
    }
    $slug = Common::convertSlug($slug);
    $url = Url::where(['urlable_type' => 'Newnet\Cms\Models\Post', 'request_path' => $slug])->first();
    return Post::findOrFail($url->urlable_id);
  }

  /**
   * Get related posts
   * @param Post $post
   */
  public function getRelatedPosts($post)
  {
    $catIds = $post->categories->pluck('id');
    $relatedPosts = null;
    if ($catIds->count() > 0) {
      $relatedPosts = Post::whereHas('categories', function ($query) use ($catIds) {
        $query->whereIn('id', $catIds);
      })->where('id', '!=', $post->id)->inRandomOrder()->limit(2)->get();
    }
    return $relatedPosts;
  }

  /**
   * Get posts archive base on the year
   * @param integer
   * @return array
   */
  public function getPostsArchive($year)
  {
    $posts = Post::whereYear('created_at', $year)->orderBy('id', 'DESC')->get();
    $countsPerMonth = [];
    $posts->each(function ($item) use (&$countsPerMonth) {
      $createdAt = \Carbon\Carbon::parse($item['created_at']);
      // Get the month and year from the created_at timestamp
      $month = $createdAt->format('n'); // 'n' returns month without leading zeros (1 to 12)
      $year = $createdAt->format('Y');

      // Increment the count for that month
      $dateObj = DateTime::createFromFormat('!m', $month);
      $monthName = $dateObj->format('F');
      $countsPerMonth[$year][$monthName] = isset($countsPerMonth[$year][$monthName]) ? $countsPerMonth[$year][$monthName] + 1 : 1;
    });
    return $countsPerMonth;
  }

  /**
   * handleErrorRedirect
   * @param string $slug
   */
  public function handleErrorRedirect($slug)
  {
    $errorRedirect = ErrorRedirect::whereFromPath($slug)->first();
    if ($errorRedirect) {
      return response()->json([
        'status' => $errorRedirect->status_code,
        'url' => $errorRedirect->to_url,
      ]);
    }
    return null;
  }

  /**
   * Get all posts in category
   * @param string $slug
   * @return object
   */
  public function getAllPostsInCategory($slug)
  {
    $url = Url::where(['urlable_type' => Category::class, 'request_path' => $slug])->first();
    if (!$url) {
      return null;
    }
    return Category::findOrFail($url->urlable_id);
  }
}
