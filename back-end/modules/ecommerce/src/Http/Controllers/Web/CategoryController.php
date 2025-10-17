<?php

namespace Modules\Ecommerce\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Ecommerce\Models\Category;

class CategoryController extends Controller
{
    public function detail($id)
    {
        $item = Category::find($id);

        return view('ecommerce::web.category.detail', compact('item'));
    }
}
