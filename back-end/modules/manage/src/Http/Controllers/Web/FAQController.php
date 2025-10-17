<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\FAQ;

class FAQController extends Controller
{
    public function detail($id)
    {
        $item = FAQ::find($id);

        return view('manage::web.faq.detail', compact('item'));
    }
}
