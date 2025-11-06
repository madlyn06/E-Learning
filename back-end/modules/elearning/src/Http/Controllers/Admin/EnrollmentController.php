<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\EnrollmentRequest;
use Modules\Elearning\Repositories\EnrollmentRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class EnrollmentController extends Controller
{
    protected EnrollmentRepository $enrollmentRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository)
    {
        $this->enrollmentRepository = $enrollmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::ENROLLMENT);
        
        $items = $this->enrollmentRepository->paginate($request->input('max', 20));

        return view('elearning::admin.enrollment.index', compact('items'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::ENROLLMENT);

        $item = $this->enrollmentRepository->find($id);

        return view('elearning::admin.enrollment.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::ENROLLMENT);

        $item = $this->enrollmentRepository->find($id);

        return view('elearning::admin.enrollment.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\EnrollmentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EnrollmentRequest $request, $id): RedirectResponse
    {
        $this->enrollmentRepository->updateById($request->all(), $id);

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.enrollments.index')
                ->with('success', __('elearning::enrollment.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.enrollments.edit', [
                'enrollment' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::enrollment.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $this->enrollmentRepository->delete($id);

        return redirect()
            ->route('elearning.admin.enrollments.index')
            ->with('success', __('elearning::enrollment.notification.deleted'));
    }
}
