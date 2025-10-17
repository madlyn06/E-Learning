<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\PostRequest;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\Eloquent\PostRepository;
use Newnet\Cms\Repositories\PostRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Actions\HandleContentListableAction;
use Newnet\Cms\Events\NewItemEvent;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Utils\EloquentUtils;

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
        $items = $this->postRepository->paginate($request->input('max', 50));

        return view('cms::admin.post.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $item = new Post();

        return view('cms::admin.post.create', compact('item'));
    }

    public function store(PostRequest $request)
    {
        $post = $this->postRepository->create($request->all());
        HandleContentListableAction::action($post);
        event(new NewItemEvent($post));
        event(new PostEvent($post));
        return redirect()
            ->route('cms.admin.post.edit', $post)
            ->with('success', __('cms::post.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $item = $this->postRepository->find($id);

        return view('cms::admin.post.edit', compact('item'));
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        if (empty($data['categories'])) {
            $data['categories'] = [];
        }
        $post = $this->postRepository->updateById($data, $id);

        event(new NewItemEvent($post));
        event(new PostEvent($post));
        return back()->with('success', __('cms::post.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->postRepository->delete($id);
        EloquentUtils::updateLatestItem(Post::class, 'migrate_post_id', 'post');
        if ($request->wantsJson()) {
            Session::flash('success', __('cms::post.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.post.index')
            ->with('success', __('cms::post.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        $this->postRepository->deleteMultiple($ids);

        Session::flash('success', __('cms::post.notification.deleted'));
        EloquentUtils::updateLatestItem(Post::class, 'migrate_post_id', 'post');
        return response()->json(['success' => 200]);
    }
}
