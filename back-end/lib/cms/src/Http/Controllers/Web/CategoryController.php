<?php

namespace Newnet\Cms\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Repositories\CategoryRepositoryInterface;
use Newnet\Cms\Repositories\Eloquent\CategoryRepository;
use Newnet\Cms\Repositories\Eloquent\PostRepository;
use Newnet\Cms\Repositories\PostRepositoryInterface;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface|CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var PostRepositoryInterface|PostRepository
     */
    private $postRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository, PostRepositoryInterface $postRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
    }

    public function detail($id, Request $request)
    {
        /** @var Category $category */
        $category = $this->categoryRepository->find($id);

        $catIds = $category->getDescendantIds();

        $defaultMax = config('cms.cms.category.item_per_page');

        $posts = $this->postRepository->paginateInCategory($catIds, $request->input('max', $defaultMax));

        return view('cms::web.category.detail', compact('category', 'posts'));
    }
}
