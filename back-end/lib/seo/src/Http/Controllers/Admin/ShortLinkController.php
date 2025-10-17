<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\Seo\Http\Requests\ShortLinkRequest;
use Newnet\Seo\Models\ShortLink;
use Newnet\Seo\Repositories\ShortLinkRepositoryInterface;
use Newnet\Seo\Repositories\Eloquent\ShortLinkRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\Events\ShortLinkEvent;

class ShortLinkController extends Controller
{
    /**
     * @var ShortLinkRepositoryInterface|ShortLinkRepository
     */
    private $shortLinkRepository;

    public function __construct(ShortLinkRepositoryInterface $shortLinkRepository)
    {
        $this->shortLinkRepository = $shortLinkRepository;
    }

    public function index(Request $request)
    {
        $items = $this->shortLinkRepository->paginate($request->input('max', 20));

        return view('seo::admin.short-link.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::SHORT_LINK);

        $item = new ShortLink();
        $item->is_active = true;

        return view('seo::admin.short-link.create', compact('item'));
    }

    public function store(ShortLinkRequest $request)
    {
        $item = $this->shortLinkRepository->create($request->all());

        event(new ShortLinkEvent('created'));

        return redirect()
            ->route('seo.admin.short-links.edit', [
                $item->id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('seo::short-link.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::SHORT_LINK);

        $item = $this->shortLinkRepository->find($id);

        return view('seo::admin.short-link.edit', compact('item'));
    }

    public function update($id, ShortLinkRequest $request)
    {
        $this->shortLinkRepository->updateById($request->all(), $id);

        event(new ShortLinkEvent('updated'));

        return back()->with('success', __('seo::short-link.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->shortLinkRepository->delete($id);

        event(new ShortLinkEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::short-link.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.short-links.index')
            ->with('success', __('seo::short-link.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        ShortLink::whereIn('id', $ids)->delete();

        event(new ShortLinkEvent('deleted'));
        Session::flash('success', __('seo::short-link.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
