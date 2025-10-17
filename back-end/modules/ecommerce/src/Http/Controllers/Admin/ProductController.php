<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Http\Requests\ProductRequest;
use Modules\Ecommerce\Repositories\ProductRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\NewItemEvent;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $items = $this->productRepository->paginate($request->input('max', 20));

        return view('ecommerce::admin.product.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::PRODUCT);

        return view('ecommerce::admin.product.create');
    }

    public function store(ProductRequest $request)
    {
        $item = $this->productRepository->create($request->all());
        event(new NewItemEvent($item));

        return redirect()
            ->route('ecommerce.admin.product.edit', [
                'product' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::product.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::PRODUCT);

        $item = $this->productRepository->find($id);

        return view('ecommerce::admin.product.edit', compact('item'));
    }

    public function update(ProductRequest $request, $id)
    {
        $item = $this->productRepository->updateById($request->all(), $id);
        event(new NewItemEvent($item));
        return back()->with('success', __('ecommerce::product.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->productRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::product.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.product.index')
            ->with('success', __('ecommerce::product.notification.deleted'));
    }
}
