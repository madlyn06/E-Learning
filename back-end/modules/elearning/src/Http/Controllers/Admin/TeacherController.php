<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\UserRequest;
use Modules\Elearning\Repositories\UserRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class TeacherController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::TEACHER);
        
        $items = $this->userRepository->getTeachers($request->input('max', 20));

        return view('elearning::admin.teacher.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::TEACHER);

        $statuses = [
            'active' => __('elearning::teacher.status_active'),
            'inactive' => __('elearning::teacher.status_inactive'),
            'pending' => __('elearning::teacher.status_pending'),
            'rejected' => __('elearning::teacher.status_rejected'),
        ];

        return view('elearning::admin.teacher.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['role'] = 'teacher';
        $item = $this->userRepository->create($data);

        return redirect()
            ->route('elearning.admin.teachers.edit', [
                'teacher' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::teacher.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::TEACHER);

        $item = $this->userRepository->find($id);
        
        if ($item->role !== 'teacher') {
            abort(404);
        }

        return view('elearning::admin.teacher.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::TEACHER);

        $item = $this->userRepository->find($id);
        
        $statuses = [
            'active' => __('elearning::teacher.status_active'),
            'inactive' => __('elearning::teacher.status_inactive'),
            'pending' => __('elearning::teacher.status_pending'),
            'rejected' => __('elearning::teacher.status_rejected'),
        ];

        return view('elearning::admin.teacher.edit', compact('item', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, $id)
    {
        $data = $request->all();
        $data['role'] = 'teacher';
        $this->userRepository->updateById($data, $id);

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.teachers.index')
                ->with('success', __('elearning::teacher.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.teachers.edit', [
                'teacher' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::teacher.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->userRepository->delete($id);

        return redirect()
            ->route('elearning.admin.teachers.index')
            ->with('success', __('elearning::teacher.notification.deleted'));
    }
}
