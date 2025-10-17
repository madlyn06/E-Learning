<?php

namespace Newnet\Acl\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Acl\AclAdminMenuKey;
use Newnet\Acl\Http\Requests\RoleRequest;
use Newnet\Acl\Repositories\RoleRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        $roles = $this->roleRepository->all();

        return view('acl::admin.role.index', compact('roles'));
    }

    public function create()
    {
        AdminMenu::activeMenu(AclAdminMenuKey::ROLE);

        return view('acl::admin.role.create');
    }

    public function store(RoleRequest $request)
    {
        $item = $this->roleRepository->create($request->all());

        return redirect()
            ->route('acl.admin.role.edit', $item)
            ->with('success', __('acl::role.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(AclAdminMenuKey::ROLE);

        $item = $this->roleRepository->find($id);

        return view('acl::admin.role.edit', compact('item'));
    }

    public function update($id, RoleRequest $request)
    {
        $this->roleRepository->update($id, $request->all());

        return back()->with('success', __('acl::role.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->roleRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('acl::role.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('acl.admin.role.index')
            ->with('success', __('acl::role.notification.deleted'));
    }
}
