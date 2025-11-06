<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\CouponRequest;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Repositories\CouponRepository;
use Modules\Elearning\Repositories\CourseRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CouponController extends Controller
{
    protected CouponRepository $couponRepository;
    protected CourseRepository $courseRepository;

    public function __construct(
        CouponRepository $couponRepository,
        CourseRepository $courseRepository
    ) {
        $this->couponRepository = $couponRepository;
        $this->courseRepository = $courseRepository;
    }

    public function index(Request $request)
    {
        $items = $this->couponRepository->paginate($request->input('max', 20));

        return view('elearning::admin.coupon.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COUPON);

        $courses = Course::pluck('name', 'id')->toArray();

        return view('elearning::admin.coupon.create', compact('courses'));
    }

    public function store(CouponRequest $request)
    {
        $item = $this->couponRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.coupon.edit', [
                'coupon' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::coupon.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COUPON);

        $item = $this->couponRepository->find($id);
        $courses = Course::pluck('name', 'id')->toArray();

        return view('elearning::admin.coupon.edit', compact('item', 'courses'));
    }

    public function update(CouponRequest $request, $id)
    {
        $this->couponRepository->update($id, $request->all());

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.coupon.index')
                ->with('success', __('elearning::coupon.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.coupon.edit', [
                'coupon' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::coupon.notification.updated'));
    }

    public function destroy($id)
    {
        $this->couponRepository->delete($id);

        return redirect()
            ->route('elearning.admin.coupon.index')
            ->with('success', __('elearning::coupon.notification.deleted'));
    }
}
