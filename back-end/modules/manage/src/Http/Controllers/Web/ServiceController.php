<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\Service;

class ServiceController extends Controller
{
    public function detail($id)
    {
        $item = Service::find($id);

        return view('manage::web.service.detail', compact('item'));
    }
}
