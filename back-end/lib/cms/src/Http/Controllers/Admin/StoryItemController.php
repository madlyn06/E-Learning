<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\Repositories\Eloquent\StoryRepository;
use Newnet\Cms\Repositories\StoryRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Events\StoryEvent;
use Newnet\Cms\Http\Requests\StoryItemRequest;
use Newnet\Cms\Models\StoryItem;
use Newnet\Cms\Repositories\StoryItemRepositoryInterface;

class StoryItemController extends Controller
{
    /**
     * @var StoryItemRepositoryInterface|StoryItemRepository
     */
    protected $storyItemRepository;

    /**
     * @var StoryRepositoryInterface|StoryRepository
     */
    protected $storyRepository;

    public function __construct(
        StoryItemRepositoryInterface $storyItemRepository,
        StoryRepositoryInterface $storyRepository
    ) {
        $this->storyItemRepository = $storyItemRepository;
        $this->storyRepository = $storyRepository;
    }

    public function index($storyId)
    {
        AdminMenu::activeMenu('story_root');

        $story = $this->storyRepository->find($storyId);
        $items = $this->storyItemRepository->allOfStory($storyId);

        return view('cms::admin.story-item.index', compact('items', 'story'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu('story_root');

        $story_id = $request->input('story_id');

        $item = new StoryItem();
        $item->story_id = $story_id;
        $item->is_active = true;
        $item->sort_order = $this->storyItemRepository->getMaxSortOrderOfStory($story_id) + 1;

        $story = $this->storyRepository->find($story_id);

        return view('cms::admin.story-item.create', compact('item', 'story'));
    }

    public function store(StoryItemRequest $request)
    {
        $item = $this->storyItemRepository->create($request->all());
        $item->attachMedia($request->input('image', []), 'image');
        event(new StoryEvent($item->story));

        if ($request->input('continue')) {
            return redirect()
                ->route('cms.admin.story-item.edit', $item->id)
                ->with('success', __('story::story-item.notification.created'));
        }

        return redirect()
            ->route('cms.admin.story-item.index', $item->story_id)
            ->with('success', __('cms::story-item.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::STORY_ALL);
        $item = $this->storyItemRepository->find($id);
        $story = $item->story;

        return view('cms::admin.story-item.edit', compact('item', 'story'));
    }

    public function update(StoryItemRequest $request, $id)
    {
        /** @var StoryItem $item */
        $item = $this->storyItemRepository->updateById($request->all(), $id);

        $imageId = $request->input('image');
        $firstMedia = $item->getFirstMedia('image');
        if ($firstMedia && $firstMedia->id != $imageId) {
            $firstMedia->delete();
        }
        $item->attachMedia([$imageId], 'image');
        event(new StoryEvent($item->story));

        if ($request->input('continue')) {
            return redirect()
                ->route('cms.admin.story-item.edit', $item->id)
                ->with('success', __('cms::story-item.notification.updated'));
        }

        return redirect()
            ->route('cms.admin.story-item.index', $item->story_id)
            ->with('success', __('cms::story-item.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $item = $this->storyItemRepository->find($id);
        $this->storyItemRepository->delete($id);
        event(new StoryEvent($item->story));

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::story-item.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.story-item.index', $item->story_id)
            ->with('success', __('cms::story-item.notification.deleted'));
    }
}
