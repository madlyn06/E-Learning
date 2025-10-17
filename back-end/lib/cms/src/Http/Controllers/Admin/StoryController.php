<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\StoryRequest;
use Newnet\Cms\Models\Story;
use Newnet\Cms\Repositories\StoryRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\DeletedStoryEvent;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Repositories\StoryItemRepositoryInterface;

class StoryController extends Controller
{
     /**
     * @var StoryItemRepositoryInterface
     */
    private $storyRepository;
    /**
     * @var StoryItemRepositoryInterface
     */
    private $storyItemRepository;

    public function __construct(StoryRepositoryInterface $storyRepository, StoryItemRepositoryInterface $storyItemRepository)
    {
        $this->storyRepository = $storyRepository;
        $this->storyItemRepository = $storyItemRepository;
    }

    public function index(Request $request)
    {
        $items = $this->storyRepository->paginate($request->input('max', 20));

        return view('cms::admin.story.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::STORY);

        $item = new Story();

        return view('cms::admin.story.create', compact('item'));
    }

    public function store(StoryRequest $request)
    {
        $story = $this->storyRepository->create($request->all());
        $post = Post::find($request->post_id);
        $post->update([
            'is_created_story' => true,
        ]);
        event(new StoryEvent($story));
        return redirect()
            ->route('cms.admin.stories.edit', $story)
            ->with('success', __('cms::story.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::STORY_ALL);

        $item = $this->storyRepository->find($id);

        $items = $this->storyItemRepository->allOfStory($id);
        $story = $item;

        return view('cms::admin.story.edit', compact('item', 'items', 'story'));
    }

    public function update($id, Request $request)
    {
        $oldItem = $this->storyRepository->find($id);
        $item = $this->storyRepository->updateById($request->all(), $id);

        $data = [
            'oldKey' => $oldItem->slug,
            'newKey' => $request->slug,
        ];

        event(new StoryEvent($item));
        return back()->with('success', __('cms::story.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $item = $this->storyRepository->find($id);
        $slug = $item->slug;

        $this->storyRepository->delete($id);
        event(new StoryEvent($item));
        event(new DeletedStoryEvent($slug));
        if ($request->wantsJson()) {
            Session::flash('success', __('cms::story.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.stories.index')
            ->with('success', __('cms::story.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        logger('ID::: ', [$ids]);
        Story::whereIn('id', $ids)->delete();

        Session::flash('success', __('cms::story.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
