<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\Brand;

class BrandController extends Controller
{
    public function detail($id)
    {
        $item = Brand::find($id);

        return view('manage::web.brand.detail', compact('item'));
    }
}
