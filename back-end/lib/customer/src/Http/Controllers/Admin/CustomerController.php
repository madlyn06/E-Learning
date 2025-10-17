<?php

namespace Newnet\Customer\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Customer\Http\Requests\CustomerRequest;
use Newnet\Customer\Repositories\CustomerRepositoryInterface;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $items = $this->customerRepository->paginate($request->input('max', 20));

        return view('customer::admin.customer.index', compact('items'));
    }

    public function create()
    {
        return view('customer::admin.customer.create');
    }

    public function store(CustomerRequest $request)
    {
        $item = $this->customerRepository->create($request->all());

        if ($request->input('continue')) {
            return redirect()
                ->route('customer.admin.customer.edit', $item->id)
                ->with('success', __('customer::customer.notification.created'));
        }

        return redirect()
            ->route('customer.admin.customer.index')
            ->with('success', __('customer::customer.notification.created'));
    }

    public function edit($id)
    {
        $item = $this->customerRepository->find($id);

        return view('customer::admin.customer.edit', compact('item'));
    }

    public function update(CustomerRequest $request, $id)
    {
        $item = $this->customerRepository->updateById($request->all(), $id);

        if ($request->input('continue')) {
            return redirect()
                ->route('customer.admin.customer.edit', $item->id)
                ->with('success', __('customer::customer.notification.updated'));
        }

        return redirect()
            ->route('customer.admin.customer.index')
            ->with('success', __('customer::customer.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        if ($request->ajax()) {
            $ids = json_decode($id);
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    $customer = $this->customerRepository->getById($id);
                    $customer->delete();
                }
            } else {
                $this->customerRepository->delete($ids);
            }
            Session::flash('success', __('customer::customer.notification.deleted'));
            return response()->json(['success' => true]);
        } else {
            $this->customerRepository->delete($id);
        }

        if ($request->wantsJson()) {
            Session::flash('success', __('customer::message.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('customer.admin.customer.index')
            ->with('success', __('customer::message.notification.deleted'));
    }
}
