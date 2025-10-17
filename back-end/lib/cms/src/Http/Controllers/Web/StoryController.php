<?php

namespace Newnet\Cms\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Models\Category;
use Newnet\Cms\Repositories\StoryRepositoryInterface;

class StoryController extends Controller
{
    /**
     * @var StoryRepositoryInterface
     */
    private $storyRepository;

    public function __construct(StoryRepositoryInterface $storyRepository)
    {
        $this->storyRepository = $storyRepository;
    }

    public function detail($id, Request $request)
    {
        /** @var Category $category */
        $story = $this->storyRepository->find($id);

        return view('cms::web.story.detail', compact('story'));
    }
}
