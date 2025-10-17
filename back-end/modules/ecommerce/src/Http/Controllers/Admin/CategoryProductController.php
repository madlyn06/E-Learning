<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Http\Requests\CategoryProductRequest;
use Modules\Ecommerce\Repositories\CategoryProductRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CategoryProductController extends Controller
{
    protected CategoryProductRepository $categoryProductRepository;

    public function __construct(CategoryProductRepository $categoryProductRepository)
    {
        $this->categoryProductRepository = $categoryProductRepository;
    }

    public function index(Request $request)
    {
        $items = $this->categoryProductRepository->paginate($request->input('max', 20));

        return view('ecommerce::admin.category-product.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::CATEGORY_PRODUCT);

        return view('ecommerce::admin.category-product.create');
    }

    public function store(CategoryProductRequest $request)
    {
        $item = $this->categoryProductRepository->create($request->all());

        return redirect()
            ->route('ecommerce.admin.category-product.edit', [
                'category_product' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::category-product.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::CATEGORY_PRODUCT);

        $item = $this->categoryProductRepository->find($id);

        return view('ecommerce::admin.category-product.edit', compact('item'));
    }

    public function update(CategoryProductRequest $request, $id)
    {
        $this->categoryProductRepository->updateById($request->all(), $id);

        return back()->with('success', __('ecommerce::category-product.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->categoryProductRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::category-product.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.category-product.index')
            ->with('success', __('ecommerce::category-product.notification.deleted'));
    }
}
