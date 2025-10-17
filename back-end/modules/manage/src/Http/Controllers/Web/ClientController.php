<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\Client;

class ClientController extends Controller
{
    public function detail($id)
    {
        $item = Client::find($id);

        return view('manage::web.client.detail', compact('item'));
    }
}
