<?php

namespace Modules\Elearning\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Course;

class CourseController extends Controller
{
    public function detail($id)
    {
        $item = Course::find($id);

        return view('elearning::web.course.detail', compact('item'));
    }
}
