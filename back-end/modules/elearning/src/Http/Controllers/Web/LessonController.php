<?php

namespace Modules\Elearning\Http\Controllers\Web;

use Illuminate\Routing\Controller;
use Modules\Elearning\Models\Lesson;

class LessonController extends Controller
{
    public function detail($id)
    {
        $item = Lesson::find($id);

        return view('elearning::web.lesson.detail', compact('item'));
    }
}
