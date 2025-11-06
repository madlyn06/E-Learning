<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\PaymentMethodRequest;
use Modules\Elearning\Repositories\PaymentMethodRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class PaymentMethodController extends Controller
{
    protected PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::PAYMENT);
        
        $items = $this->paymentMethodRepository->paginate($request->input('max', 20));

        return view('elearning::admin.payment-method.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::PAYMENT);
        $item = null;
        return view('elearning::admin.payment-method.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\PaymentMethodRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PaymentMethodRequest $request)
    {
        $item = $this->paymentMethodRepository->create($request->all());
        return redirect()
            ->route('elearning.admin.payment-methods.edit', [
                'payment_method' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::payment_method.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::PAYMENT);

        $item = $this->paymentMethodRepository->find($id);

        return view('elearning::admin.payment-method.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::PAYMENT);

        $item = $this->paymentMethodRepository->find($id);

        return view('elearning::admin.payment-method.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\PaymentMethodRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PaymentMethodRequest $request, $id)
    {
        $this->paymentMethodRepository->updateById($request->all(), $id);
        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.payment-methods.index')
                ->with('success', __('elearning::payment.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.payment-methods.edit', [
                'payment_method' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::payment.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->paymentMethodRepository->delete($id);

        return redirect()
            ->route('elearning.admin.payment-methods.index')
            ->with('success', __('elearning::payment.notification.deleted'));
    }

    public function config($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::PAYMENT);

        $item = $this->paymentMethodRepository->find($id);

        return view('elearning::admin.payment-method.config', compact('item'));
    }

    public function configUpdate(Request $request, $id)
    {
        $this->paymentMethodRepository->updateById($request->all(), $id);
        return redirect()
            ->route('elearning.admin.payment-methods.config', $id)
            ->with('success', __('elearning::payment_method.notification.updated_config'));
    }
}
