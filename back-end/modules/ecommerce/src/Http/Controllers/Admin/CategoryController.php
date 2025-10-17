<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Http\Requests\CategoryRequest;
use Modules\Ecommerce\Models\Category;
use Modules\Ecommerce\Repositories\CategoryRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->categoryRepository->paginateTree($request->input('max', 20));

        return view('ecommerce::admin.category.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::CATEGORY);
        $item = new Category();
        $item->is_active = true;
        $item->parent_id = $request->input('parent_id');

        return view('ecommerce::admin.category.create', compact('item'));
    }

    public function store(CategoryRequest $request)
    {
        $item = $this->categoryRepository->create($request->all());

        return redirect()
            ->route('ecommerce.admin.category.edit', [
                'category' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::category.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::CATEGORY);

        $item = $this->categoryRepository->find($id);

        return view('ecommerce::admin.category.edit', compact('item'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $this->categoryRepository->updateById($request->all(), $id);

        return back()->with('success', __('ecommerce::category.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->categoryRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::category.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.category.index')
            ->with('success', __('ecommerce::category.notification.deleted'));
    }
}
