<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\CustomerGroup;

class CustomerGroupController extends Controller
{
    public function detail($id)
    {
        $item = CustomerGroup::find($id);

        return view('ecommerce::web.customer-group.detail', compact('item'));
    }
}
