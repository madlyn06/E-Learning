<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\Page;

class PageController extends Controller
{
    public function detail($id)
    {
        $item = Page::find($id);

        return view('manage::web.page.detail', compact('item'));
    }
}
