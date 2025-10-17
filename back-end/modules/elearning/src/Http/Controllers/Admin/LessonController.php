<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\LessonRequest;
use Modules\Elearning\Repositories\LessonRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class LessonController extends Controller
{
    protected LessonRepository $lessonRepository;

    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function index(Request $request)
    {
        $items = $this->lessonRepository->paginate($request->input('max', 20));

        return view('elearning::admin.lesson.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::LESSON);

        return view('elearning::admin.lesson.create');
    }

    public function store(LessonRequest $request)
    {
        $item = $this->lessonRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.lesson.edit', [
                'lesson' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::lesson.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::LESSON);

        $item = $this->lessonRepository->find($id);

        return view('elearning::admin.lesson.edit', compact('item'));
    }

    public function update(LessonRequest $request, $id)
    {
        $this->lessonRepository->updateById($request->all(), $id);

        return back()->with('success', __('elearning::lesson.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->lessonRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('elearning::lesson.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('elearning.admin.lesson.index')
            ->with('success', __('elearning::lesson.notification.deleted'));
    }
}
