<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\ServiceRequest;
use Modules\Manage\Repositories\ServiceRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\NewItemEvent;

class ServiceController extends Controller
{
    protected ServiceRepository $serviceRepository;

    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    public function index(Request $request)
    {
        $items = $this->serviceRepository->paginate($request->input('max', 20));

        return view('manage::admin.service.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::SERVICE);

        return view('manage::admin.service.create');
    }

    public function store(ServiceRequest $request)
    {
        $item = $this->serviceRepository->create($request->all());

        event(new NewItemEvent($item));

        return redirect()
            ->route('manage.admin.service.edit', [
                'service' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::service.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::SERVICE);

        $item = $this->serviceRepository->find($id);

        return view('manage::admin.service.edit', compact('item'));
    }

    public function update(ServiceRequest $request, $id)
    {
        $item = $this->serviceRepository->updateById($request->all(), $id);

        event(new NewItemEvent($item));

        return back()->with('success', __('manage::service.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->serviceRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::service.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.service.index')
            ->with('success', __('manage::service.notification.deleted'));
    }
}
