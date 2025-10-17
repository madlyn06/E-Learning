<?php

namespace Newnet\Cms\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Cms\Repositories\Eloquent\PostRepository;
use Newnet\Cms\Repositories\PostRepositoryInterface;

class PostController extends Controller
{
    /**
     * @var PostRepositoryInterface|PostRepository
     */
    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(Request $request)
    {
        $posts = $this->postRepository->paginate($request->input('max', 20));

        return view('cms::web.post.index', compact('posts'));
    }

    public function detail($id, Request $request)
    {
        $post = $this->postRepository->find($id);
        if ($post){
            $totalViewed = $post->is_viewed + 1;
            $post->update(['is_viewed' => $totalViewed]);
        }

        return view('cms::web.post.detail', compact('post'));
    }
}
