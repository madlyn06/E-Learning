<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\FileCategory;

class FileCategoryController extends Controller
{
    public function detail($id)
    {
        $item = FileCategory::find($id);

        return view('manage::web.file-category.detail', compact('item'));
    }
}
