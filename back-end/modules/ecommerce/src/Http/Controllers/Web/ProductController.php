<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\Product;

class ProductController extends Controller
{
    public function detail($id)
    {
        $item = Product::find($id);

        return view('ecommerce::web.product.detail', compact('item'));
    }
}
