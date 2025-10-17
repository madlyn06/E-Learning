<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\CategoryProduct;

class CategoryProductController extends Controller
{
    public function detail($id)
    {
        $item = CategoryProduct::find($id);

        return view('ecommerce::web.category-product.detail', compact('item'));
    }
}
