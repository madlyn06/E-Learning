<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\OrderItem;

class OrderItemController extends Controller
{
    public function detail($id)
    {
        $item = OrderItem::find($id);

        return view('ecommerce::web.order-item.detail', compact('item'));
    }
}
