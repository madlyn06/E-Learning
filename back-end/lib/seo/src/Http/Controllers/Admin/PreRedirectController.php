<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Seo\Http\Requests\PreRedirectRequest;
use Newnet\Seo\Repositories\PreRedirectRepositoryInterface;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;

class PreRedirectController extends Controller
{
    /**
     * @var PreRedirectRepositoryInterface
     */
    protected $preRedirectRepository;

    public function __construct(PreRedirectRepositoryInterface $preRedirectRepository)
    {
        $this->preRedirectRepository = $preRedirectRepository;
    }

    public function index(Request $request)
    {
        $items = $this->preRedirectRepository->paginate($request->input('max', 20));

        return view('seo::admin.pre-redirect.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::PRE_REDIRECT);

        return view('seo::admin.pre-redirect.create');
    }

    public function store(PreRedirectRequest $request)
    {
        $item = $this->preRedirectRepository->create($request->all());

        return redirect()
            ->route('seo.admin.pre-redirect.edit', $item)
            ->with('success', __('seo::pre-redirect.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::PRE_REDIRECT);

        $item = $this->preRedirectRepository->find($id);

        return view('seo::admin.pre-redirect.edit', compact('item'));
    }

    public function update(PreRedirectRequest $request, $id)
    {
        $this->preRedirectRepository->updateById($request->all(), $id);

        return back()->with('success', __('seo::pre-redirect.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->preRedirectRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::pre-redirect.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.pre-redirect.index')
            ->with('success', __('seo::pre-redirect.notification.deleted'));
    }
}
