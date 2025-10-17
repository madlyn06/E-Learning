<?php

namespace Modules\StaticBlock\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\StaticBlock\Models\StaticBlock;

class StaticBlockController extends Controller
{
    public function detail($id)
    {
        $item = StaticBlock::find($id);

        return view('staticblock::web.static-block.detail', compact('item'));
    }
}
