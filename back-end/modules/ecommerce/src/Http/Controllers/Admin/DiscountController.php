<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Http\Requests\DiscountRequest;
use Modules\Ecommerce\Repositories\DiscountRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class DiscountController extends Controller
{
    protected DiscountRepository $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function index(Request $request)
    {
        $items = $this->discountRepository->paginate($request->input('max', 20));

        return view('ecommerce::admin.discount.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::DISCOUNT);

        return view('ecommerce::admin.discount.create');
    }

    public function store(DiscountRequest $request)
    {
        $item = $this->discountRepository->create($request->all());

        return redirect()
            ->route('ecommerce.admin.discounts.edit', [
                'discount' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::discount.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::DISCOUNT);

        $item = $this->discountRepository->find($id);

        return view('ecommerce::admin.discount.edit', compact('item'));
    }

    public function update(DiscountRequest $request, $id)
    {
        $this->discountRepository->updateById($request->all(), $id);

        return back()->with('success', __('ecommerce::discount.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->discountRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::discount.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.discounts.index')
            ->with('success', __('ecommerce::discount.notification.deleted'));
    }
}
