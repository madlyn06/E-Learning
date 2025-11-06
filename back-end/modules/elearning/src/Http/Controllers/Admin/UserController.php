<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\UserRequest;
use Modules\Elearning\Models\User;
use Modules\Elearning\Repositories\UserRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class UserController extends Controller
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
        AdminMenu::activeMenu(ElearningAdminMenuKey::STUDENT);
        
        $items = $this->userRepository->getStudents($request->input('max', 20));

        return view('elearning::admin.user.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::STUDENT);

        $statuses = [
            'active' => __('elearning::user.status_active'),
            'inactive' => __('elearning::user.status_inactive'),
        ];
        
        $roles = [
            'student' => __('elearning::user.role_student'),
            'teacher' => __('elearning::user.role_teacher'),
            'admin' => __('elearning::user.role_admin'),
        ];

        return view('elearning::admin.user.create', compact('statuses', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $item = $this->userRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.users.edit', [
                'user' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::user.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::STUDENT);

        $item = $this->userRepository->find($id);

        return view('elearning::admin.user.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::STUDENT);

        $item = $this->userRepository->find($id);
        
        $statuses = [
            'active' => __('elearning::user.status_active'),
            'inactive' => __('elearning::user.status_inactive'),
        ];
        
        $roles = [
            'student' => __('elearning::user.role_student'),
            'teacher' => __('elearning::user.role_teacher'),
            'admin' => __('elearning::user.role_admin'),
        ];

        return view('elearning::admin.user.edit', compact('item', 'statuses', 'roles'));
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
        $this->userRepository->updateById($request->all(), $id);

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.users.index')
                ->with('success', __('elearning::user.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.users.edit', [
                'user' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::user.notification.updated'));
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
            ->route('elearning.admin.users.index')
            ->with('success', __('elearning::user.notification.deleted'));
    }
}
