<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\CourseRequest;
use Modules\Elearning\Repositories\CourseRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CourseController extends Controller
{
    protected CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function index(Request $request)
    {
        $items = $this->courseRepository->paginate($request->input('max', 20));

        return view('elearning::admin.course.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COURSE);

        return view('elearning::admin.course.create');
    }

    public function store(CourseRequest $request)
    {
        $item = $this->courseRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.course.edit', [
                'course' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::course.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COURSE);

        $item = $this->courseRepository->find($id);

        return view('elearning::admin.course.edit', compact('item'));
    }

    public function update(CourseRequest $request, $id)
    {
        $this->courseRepository->updateById($request->all(), $id);

        return back()->with('success', __('elearning::course.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->courseRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('elearning::course.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('elearning.admin.course.index')
            ->with('success', __('elearning::course.notification.deleted'));
    }
}
