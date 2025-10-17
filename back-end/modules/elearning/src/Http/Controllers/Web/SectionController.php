<?php

namespace Modules\Elearning\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Section;

class SectionController extends Controller
{
    public function detail($id)
    {
        $item = Section::find($id);

        return view('elearning::web.section.detail', compact('item'));
    }
}
