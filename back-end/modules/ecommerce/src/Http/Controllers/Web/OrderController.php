<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\Order;

class OrderController extends Controller
{
    public function detail($id)
    {
        $item = Order::find($id);

        return view('ecommerce::web.order.detail', compact('item'));
    }
}
