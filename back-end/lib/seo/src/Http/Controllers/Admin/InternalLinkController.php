<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\Http\Requests\InternalLinkRequest;
use Newnet\Seo\Repositories\InternalLinkRepositoryInterface;
use Newnet\Seo\SeoAdminMenuKey;

class InternalLinkController extends Controller
{
    protected InternalLinkRepositoryInterface $internalLinkRepository;

    public function __construct(InternalLinkRepositoryInterface $internalLinkRepository)
    {
        $this->internalLinkRepository = $internalLinkRepository;
    }

    public function index(Request $request)
    {
        $items = $this->internalLinkRepository->paginate($request->input('max', 20));

        return view('seo::admin.internal-link.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::SEO_INTERAL_LINK);

        return view('seo::admin.internal-link.create');
    }

    public function store(InternalLinkRequest $request)
    {
        $item = $this->internalLinkRepository->create($request->all());

        return redirect()
            ->route('seo.admin.internal-links.edit', [
                'internal_link' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('seo::internal-link.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::SEO_INTERAL_LINK);

        $item = $this->internalLinkRepository->find($id);

        return view('seo::admin.internal-link.edit', compact('item'));
    }

    public function update(InternalLinkRequest $request, $id)
    {
        $this->internalLinkRepository->updateById($request->all(), $id);

        return back()->with('success', __('seo::internal-link.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->internalLinkRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::internal-link.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.internal-links.index')
            ->with('success', __('seo::internal-link.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        $this->internalLinkRepository->deleteMultiple($ids);
        Session::flash('success', __('seo::internal-link.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
