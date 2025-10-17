<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Repositories\OrderRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $items = $this->orderRepository->paginate($request->input('max', 20));

        return view('ecommerce::admin.order.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::ORDER);

        return view('ecommerce::admin.order.create');
    }

    public function store(Request $request)
    {
        $item = $this->orderRepository->create($request->all());

        return redirect()
            ->route('ecommerce.admin.order.edit', [
                'order' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::order.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::ORDER);

        $item = $this->orderRepository->find($id);

        return view('ecommerce::admin.order.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $this->orderRepository->updateById($request->all(), $id);

        return back()->with('success', __('ecommerce::order.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->orderRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::order.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.order.index')
            ->with('success', __('ecommerce::order.notification.deleted'));
    }
}
