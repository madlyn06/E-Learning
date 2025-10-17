<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\ClientEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\ClientRequest;
use Modules\Manage\Repositories\ClientRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class ClientController extends Controller
{
    protected ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index(Request $request)
    {
        $items = $this->clientRepository->paginate($request->input('max', 20));

        return view('manage::admin.client.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::CLIENT);

        return view('manage::admin.client.create');
    }

    public function store(ClientRequest $request)
    {
        $item = $this->clientRepository->create($request->all());

        event(new ClientEvent('created'));

        return redirect()
            ->route('manage.admin.client.edit', [
                'client' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::client.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::CLIENT);

        $item = $this->clientRepository->find($id);

        return view('manage::admin.client.edit', compact('item'));
    }

    public function update(ClientRequest $request, $id)
    {
        $request->validate(['stars' => 'max:5']);
        $this->clientRepository->updateById($request->all(), $id);
        event(new ClientEvent('updated'));
        return back()->with('success', __('manage::client.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->clientRepository->delete($id);
        event(new ClientEvent('deleted'));
        if ($request->wantsJson()) {
            Session::flash('success', __('manage::client.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.client.index')
            ->with('success', __('manage::client.notification.deleted'));
    }
}
