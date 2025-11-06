<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\ReviewRequest;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\Review;
use Modules\Elearning\Models\User;
use Modules\Elearning\Repositories\ReviewRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class ReviewController extends Controller
{
    protected ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::REVIEW);
        
        $items = $this->reviewRepository->paginate($request->input('max', 20));

        return view('elearning::admin.review.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::REVIEW);

        $users = User::pluck('name', 'id')->toArray();
        $courses = Course::pluck('name', 'id')->toArray();
        $statuses = [
            'pending' => __('elearning::review.status_pending'),
            'approved' => __('elearning::review.status_approved'),
            'rejected' => __('elearning::review.status_rejected'),
        ];
        $ratings = [
            1 => '1 ' . __('elearning::review.star'),
            2 => '2 ' . __('elearning::review.stars'),
            3 => '3 ' . __('elearning::review.stars'),
            4 => '4 ' . __('elearning::review.stars'),
            5 => '5 ' . __('elearning::review.stars'),
        ];

        return view('elearning::admin.review.create', compact('users', 'courses', 'statuses', 'ratings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\ReviewRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReviewRequest $request)
    {
        $item = $this->reviewRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.reviews.edit', [
                'review' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::review.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::REVIEW);

        $item = $this->reviewRepository->find($id);

        return view('elearning::admin.review.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::REVIEW);

        $item = $this->reviewRepository->find($id);
        $users = User::pluck('name', 'id')->toArray();
        $courses = Course::pluck('name', 'id')->toArray();
        $statuses = [
            'pending' => __('elearning::review.status_pending'),
            'approved' => __('elearning::review.status_approved'),
            'rejected' => __('elearning::review.status_rejected'),
        ];
        $ratings = [
            1 => '1 ' . __('elearning::review.star'),
            2 => '2 ' . __('elearning::review.stars'),
            3 => '3 ' . __('elearning::review.stars'),
            4 => '4 ' . __('elearning::review.stars'),
            5 => '5 ' . __('elearning::review.stars'),
        ];

        return view('elearning::admin.review.edit', compact('item', 'users', 'courses', 'statuses', 'ratings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\ReviewRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReviewRequest $request, $id)
    {
        $this->reviewRepository->updateById($request->all(), $id);

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.reviews.index')
                ->with('success', __('elearning::review.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.reviews.edit', [
                'review' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::review.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->reviewRepository->delete($id);

        return redirect()
            ->route('elearning.admin.reviews.index')
            ->with('success', __('elearning::review.notification.deleted'));
    }
}
