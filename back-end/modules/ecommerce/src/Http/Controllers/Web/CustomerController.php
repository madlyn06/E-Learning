<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\Customer;

class CustomerController extends Controller
{
    public function detail($id)
    {
        $item = Customer::find($id);

        return view('ecommerce::web.customer.detail', compact('item'));
    }
}
