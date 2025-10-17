<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\PortfolioProjectEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\PortfolioProjectRequest;
use Modules\Manage\Repositories\PortfolioProjectRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\NewItemEvent;

class PortfolioProjectController extends Controller
{
    protected PortfolioProjectRepository $portfolioProjectRepository;

    public function __construct(PortfolioProjectRepository $portfolioProjectRepository)
    {
        $this->portfolioProjectRepository = $portfolioProjectRepository;
    }

    public function index(Request $request)
    {
        $items = $this->portfolioProjectRepository->paginate($request->input('max', 20));

        return view('manage::admin.portfolio-project.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PORTFOLIO_PROJECT);

        return view('manage::admin.portfolio-project.create');
    }

    public function store(PortfolioProjectRequest $request)
    {
        $item = $this->portfolioProjectRepository->create($request->all());

        event(new NewItemEvent($item));

        return redirect()
            ->route('manage.admin.portfolio-projects.edit', [
                'portfolio_project' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::portfolio-project.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PORTFOLIO_PROJECT);

        $item = $this->portfolioProjectRepository->find($id);

        return view('manage::admin.portfolio-project.edit', compact('item'));
    }

    public function update(PortfolioProjectRequest $request, $id)
    {
        $item = $this->portfolioProjectRepository->updateById($request->all(), $id);

        event(new NewItemEvent($item));

        return back()->with('success', __('manage::portfolio-project.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->portfolioProjectRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::portfolio-project.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.portfolio-projects.index')
            ->with('success', __('manage::portfolio-project.notification.deleted'));
    }
}
