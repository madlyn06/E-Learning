<?php

namespace Modules\Manage\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Manage\Models\File;

class FileController extends Controller
{
    public function detail($id)
    {
        $item = File::find($id);

        return view('manage::web.file.detail', compact('item'));
    }
}
