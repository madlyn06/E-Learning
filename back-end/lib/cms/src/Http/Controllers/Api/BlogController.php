<?php

namespace Newnet\Cms\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Exceptions\BlogException;
use Newnet\Cms\Factory\ResourceFactory;
use Newnet\Cms\Http\Resources\CategoryDetailResource;
use Newnet\Cms\Http\Resources\CategoryResource;
use Newnet\Cms\Http\Resources\ContentListResource;
use Newnet\Cms\Http\Resources\KeywordResource;
use Newnet\Cms\Http\Resources\LatestPostResource;
use Newnet\Cms\Http\Resources\PageResource;
use Newnet\Cms\Http\Resources\PostDetailResource;
use Newnet\Cms\Http\Resources\PostInCategoryResource;
use Newnet\Cms\Http\Resources\PostResource;
use Newnet\Cms\Http\Resources\PostTagResource;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\PageRepositoryInterface;
use Newnet\Cms\Services\BlogService;
use Newnet\Cms\Utils\EloquentUtils;
use Newnet\Core\Utils\Common;
use Newnet\Seo\Models\Url;
use Newnet\Tag\Http\Resources\TagResource;

class BlogController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Get categories
     */
    public function categories()
    {
        $categories = $this->blogService->getCategories();
        return CategoryResource::collection($categories ?? []);
    }

    /**
     * Get posts by category id
     */
    public function categoryDetail($id)
    {
        $itemOnPage = setting('item_on_page', 10);
        $category = $this->blogService->getCategoryDetail($id);
        $posts = $category->posts()->with(['author', 'categories', 'comments' => function ($query) {
            $query->where('is_published', 1);
        }])->where('is_active', 1)->orderBy('published_at', 'DESC')->paginate($itemOnPage);
        return [
            'posts' => PostResource::collection($posts)->response()->getData(true),
            'category' => new CategoryResource($category)
        ];
    }

    /**
     * Get category detail
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
        $category = Category::findOrFail($url->urlable_id);
        return new CategoryDetailResource($category);
    }

    /**
     * Get posts or search
     */
    public function posts(Request $request)
    {
        $posts = $this->blogService->getPosts($request);
        return PostResource::collection($posts ?? []);
    }

    public function pages()
    {
        $itemOnPage = setting('item_on_page', 10);
        $pages = app(PageRepositoryInterface::class)->paginate($itemOnPage);
        return PageResource::collection($pages ?? []);
    }

    /**
     * Get post by slug
     */
    public function getPostBySlug($slug)
    {
        $post = $this->blogService->getPostBySlug($slug);
        $relatedPosts = $this->blogService->getRelatedPosts($post);
        return [
            'post' => new PostDetailResource($post),
            'content_list' => ContentListResource::collection($post->contentList ?? []),
            'comments' => Common::buildCommentTree($post->comments()->where('is_published', true)->get()),
            'related_posts' => $relatedPosts ? PostDetailResource::collection($relatedPosts ?? []) : [],
            'tags' => TagResource::collection($post->tags ?? []),
            'keywords' => KeywordResource::collection($post->keywords ?? []),
            'posts_tag' => PostTagResource::collection($post->relatedPostsTag() ?? []),
        ];
    }

    public function getItemBySlug($slug)
    {
        $item = EloquentUtils::transferModel($slug);
        if (!$item) {
            return response()->json([
                'message' => 'Page not found',
            ], 400);
        }

        $response = ResourceFactory::createResource($item);
        if ($response instanceof BlogException) {
            // Handle error redirect
            $result = $this->blogService->handleErrorRedirect($slug);
            if ($result) {
                return $result;
            }
            return response()->json([
                'message' => 'Page not found',
            ], 400);
        }
        return $response;
    }

    /**
     * Get archirves
     */
    public function getArchives(Request $request)
    {
        $year = $request->year;
        if (!$year) {
            $year = date('Y');
        }
        $countsPerMonth = $this->blogService->getPostsArchive($year);
        return response()->json(isset($countsPerMonth[$year]) ? $countsPerMonth[$year] : [$year => 0]);
    }

    public function getRecentPosts()
    {
        $itemOnPage = request('per_page') ?? 4;
        $posts = Post::where('is_active', true)->orderBy('id', 'DESC')->paginate($itemOnPage);
        return LatestPostResource::collection($posts ?? []);
    }

    /**
     * Get all posts of category
     */
    public function getAllPostsInCategory($categorySlug)
    {
        $category = $this->blogService->getAllPostsInCategory($categorySlug);
        if (!$category) {
            return response()->json([
                'message' => 'Page not found',
            ], 400);
        }
        return [
            'category' => new CategoryDetailResource($category),
            'posts' => PostInCategoryResource::collection($category->posts()->get() ?? [])
        ];
    }

    /**
     * Get all posts
     */
    public function getAllPosts()
    {
        return PostResource::collection(Post::all());
    }

    /**
     * Get all categories
     */
    public function getAllCategories()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Get total items
     * @return int
     */
    public function getTotalItems($type)
    {
        switch ($type) {
            case 'category':
                break;
            case 'posts':
                return Post::whereIsActive(true)->count();
                break;
        }
        return 0;
    }

    public function getRelatedPosts($slug)
    {
        $post = $this->blogService->getPostBySlug($slug);
        $relatedPosts = $this->blogService->getRelatedPosts($post);
        return $relatedPosts ? PostDetailResource::collection($relatedPosts ?? []) : [];
    }
}
