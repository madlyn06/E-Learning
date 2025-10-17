<?php
namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Http\Requests\KeywordRequest;
use Newnet\Cms\Repositories\Eloquent\KeywordRepository;
use Newnet\Cms\Repositories\KeywordRepositoryInterface;
use Newnet\Cms\Events\KeywordEvent;
use Newnet\Cms\Models\Keyword;

class KeywordController extends Controller
{
    /**
     * @var KeywordRepositoryInterface|KeywordRepository
     */
    private $keywordRepository;

    public function __construct(KeywordRepositoryInterface $keywordRepository)
    {
        $this->keywordRepository = $keywordRepository;
    }

    public function index(Request $request)
    {
        $items = $this->keywordRepository->paginate($request->input('max', 20));

        return view('cms::admin.keywords.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::KEYWORD);

        return view('cms::admin.keywords.create');
    }

    public function store(KeywordRequest $request)
    {
        /** @var Keyword $tag */
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $tag = $this->keywordRepository->create($data);
        event(new KeywordEvent('created'));

        return redirect()
            ->route('cms.admin.keywords.edit', $tag->id)
            ->with('success', __('cms::keyword.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::KEYWORD);

        $item = $this->keywordRepository->find($id);

        return view('cms::admin.keywords.edit', compact('item'));
    }

    public function update($id, KeywordRequest $request)
    {
        $this->keywordRepository->updateById($request->all(), $id);
        event(new KeywordEvent('updated'));

        return back()->with('success', __('cms::keyword.notification.created'));
    }

    public function destroy($id, Request $request)
    {
        $this->keywordRepository->delete($id);
        event(new KeywordEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('cms::keyword.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.keywords.index')
            ->with('success', __('cms::keyword.notification.deleted'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');
        $keywords = array_filter(explode(' ', trim($keyword)));

        $tags = Keyword::query()->where(function($q) use ($keywords){
            foreach ($keywords as $keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            }
        })->orWhere(function($q) use ($keywords){
            foreach ($keywords as $keyword) {
                $q->where('slug', 'like', "%{$keyword}%");
            }
        })->paginate();

        $items = [];
        foreach ($tags as $tag) {
            $items[] = [
                'id' => $tag->id,
                'text' => $tag->name,
            ];
        }

        return [
            'items' => $items,
            'hasMore' => $tags->hasMorePages(),
        ];
    }


    public function destroyMultipleItems(Request $request)
    {
        $this->keywordRepository->deleteMultiple($request->ids);
        event(new KeywordEvent('deleted'));
        Session::flash('success', __('cms::keyword.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
