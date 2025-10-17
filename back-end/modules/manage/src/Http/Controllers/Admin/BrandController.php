<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\BrandEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\BrandRequest;
use Modules\Manage\Repositories\BrandRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class BrandController extends Controller
{
    protected BrandRepository $brandRepository;

    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function index(Request $request)
    {
        $items = $this->brandRepository->paginate($request->input('max', 20));

        return view('manage::admin.brand.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::BRAND);

        return view('manage::admin.brand.create');
    }

    public function store(BrandRequest $request)
    {
        $item = $this->brandRepository->create($request->all());

        event(new BrandEvent('created'));

        return redirect()
            ->route('manage.admin.brand.edit', [
                'brand' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::brand.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::BRAND);

        $item = $this->brandRepository->find($id);

        return view('manage::admin.brand.edit', compact('item'));
    }

    public function update(BrandRequest $request, $id)
    {
        $this->brandRepository->updateById($request->all(), $id);
        event(new BrandEvent('updated'));
        return back()->with('success', __('manage::brand.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->brandRepository->delete($id);
        event(new BrandEvent('deleted'));
        if ($request->wantsJson()) {
            Session::flash('success', __('manage::brand.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.brand.index')
            ->with('success', __('manage::brand.notification.deleted'));
    }
}
