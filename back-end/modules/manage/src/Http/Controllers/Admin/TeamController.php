<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\TeamEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\TeamRequest;
use Modules\Manage\Repositories\TeamRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class TeamController extends Controller
{
    protected TeamRepository $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function index(Request $request)
    {
        $items = $this->teamRepository->paginate($request->input('max', 20));

        return view('manage::admin.team.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::TEAM);

        return view('manage::admin.team.create');
    }

    public function store(TeamRequest $request)
    {
        $item = $this->teamRepository->create($request->all());
        event(new TeamEvent('created'));
        return redirect()
            ->route('manage.admin.team.edit', [
                'team' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::team.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::TEAM);

        $item = $this->teamRepository->find($id);

        return view('manage::admin.team.edit', compact('item'));
    }

    public function update(TeamRequest $request, $id)
    {
        $this->teamRepository->updateById($request->all(), $id);
        event(new TeamEvent('updated'));

        return back()->with('success', __('manage::team.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->teamRepository->delete($id);
        event(new TeamEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::team.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.team.index')
            ->with('success', __('manage::team.notification.deleted'));
    }
}
