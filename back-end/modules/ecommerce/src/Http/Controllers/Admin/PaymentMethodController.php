<?php

namespace Modules\Ecommerce\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Ecommerce\EcommerceAdminMenuKey;
use Modules\Ecommerce\Http\Requests\PaymentMethodRequest;
use Modules\Ecommerce\Repositories\PaymentMethodRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class PaymentMethodController extends Controller
{
    protected PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function index(Request $request)
    {
        $items = $this->paymentMethodRepository->paginate($request->input('max', 20));

        return view('ecommerce::admin.payment-method.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::PAYMENT_METHOD);

        return view('ecommerce::admin.payment-method.create');
    }

    public function store(PaymentMethodRequest $request)
    {
        $item = $this->paymentMethodRepository->create($request->all());

        return redirect()
            ->route('ecommerce.admin.payment-methods.edit', [
                'payment_method' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('ecommerce::payment-method.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(EcommerceAdminMenuKey::PAYMENT_METHOD);

        $item = $this->paymentMethodRepository->find($id);

        return view('ecommerce::admin.payment-method.edit', compact('item'));
    }

    public function update(PaymentMethodRequest $request, $id)
    {
        $this->paymentMethodRepository->updateById($request->all(), $id);

        return back()->with('success', __('ecommerce::payment-method.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->paymentMethodRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('ecommerce::payment-method.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('ecommerce.admin.payment-methods.index')
            ->with('success', __('ecommerce::payment-method.notification.deleted'));
    }
}
