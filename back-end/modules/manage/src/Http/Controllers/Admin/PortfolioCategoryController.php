<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\PortfolioCategoryEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\ServiceRequest;
use Modules\Manage\Repositories\PortfolioCategoryRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class PortfolioCategoryController extends Controller
{
    protected PortfolioCategoryRepository $portfolioCategoryRepository;

    public function __construct(PortfolioCategoryRepository $portfolioCategoryRepository)
    {
        $this->portfolioCategoryRepository = $portfolioCategoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->portfolioCategoryRepository->paginate($request->input('max', 20));

        return view('manage::admin.portfolio-category.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PORTFOLIO_CATEGORY);

        return view('manage::admin.portfolio-category.create');
    }

    public function store(ServiceRequest $request)
    {
        $item = $this->portfolioCategoryRepository->create($request->all());

        event(new PortfolioCategoryEvent('created'));

        return redirect()
            ->route('manage.admin.portfolio-categories.edit', [
                'portfolio_category' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::portfolio-category.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::PORTFOLIO_CATEGORY);

        $item = $this->portfolioCategoryRepository->find($id);

        return view('manage::admin.portfolio-category.edit', compact('item'));
    }

    public function update(ServiceRequest $request, $id)
    {
        $this->portfolioCategoryRepository->updateById($request->all(), $id);

        event(new PortfolioCategoryEvent('updated'));

        return back()->with('success', __('manage::portfolio-category.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->portfolioCategoryRepository->delete($id);

        event(new PortfolioCategoryEvent('updated'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::portfolio-category.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.portfolio-category.index')
            ->with('success', __('manage::portfolio-category.notification.deleted'));
    }
}
