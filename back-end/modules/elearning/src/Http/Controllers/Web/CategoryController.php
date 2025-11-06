<?php

namespace Modules\Elearning\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Category;

class CategoryController extends Controller
{
    public function detail($id)
    {
        $item = Category::find($id);

        return view('elearning::web.category.detail', compact('item'));
    }
}
