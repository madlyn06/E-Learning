<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\MembershipRequest;
use Modules\Elearning\Repositories\MembershipRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class MembershipController extends Controller
{
    protected MembershipRepository $membershipRepository;

    public function __construct(MembershipRepository $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function index(Request $request)
    {
        $items = $this->membershipRepository->paginate($request->input('max', 20));

        return view('elearning::admin.membership.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::MEMBERSHIP);

        return view('elearning::admin.membership.create');
    }

    public function store(MembershipRequest $request)
    {
        $item = $this->membershipRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.membership.edit', [
                'membership' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::membership.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::MEMBERSHIP);

        $item = $this->membershipRepository->find($id);

        return view('elearning::admin.membership.edit', compact('item'));
    }

    public function update(MembershipRequest $request, $id)
    {
        $this->membershipRepository->update($id, $request->all());

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.memberships.index')
                ->with('success', __('elearning::membership.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.membership.edit', [
                'membership' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::membership.notification.updated'));
    }

    public function destroy($id)
    {
        $this->membershipRepository->delete($id);

        return redirect()
            ->route('elearning.admin.memberships.index')
            ->with('success', __('elearning::membership.notification.deleted'));
    }
}
