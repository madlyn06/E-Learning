<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\CommentRequest;
use Modules\Elearning\Models\Lesson;
use Modules\Elearning\Models\User;
use Modules\Elearning\Repositories\CommentRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class CommentController extends Controller
{
    protected CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COMMENT);
        
        $items = $this->commentRepository->paginate($request->input('max', 20));

        return view('elearning::admin.comment.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COMMENT);

        $users = User::pluck('name', 'id')->toArray();
        $lessons = Lesson::pluck('title', 'id')->toArray();
        $parentComments = $this->commentRepository->model->whereNull('parent_id')->pluck('content', 'id')->toArray();

        return view('elearning::admin.comment.create', compact('users', 'lessons', 'parentComments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\CommentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentRequest $request)
    {
        $item = $this->commentRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.comments.edit', [
                'comment' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::comment.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COMMENT);

        $item = $this->commentRepository->find($id);

        return view('elearning::admin.comment.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::COMMENT);

        $item = $this->commentRepository->find($id);
        $users = User::pluck('name', 'id')->toArray();
        $lessons = Lesson::pluck('title', 'id')->toArray();
        $parentComments = $this->commentRepository->getModel()->whereNull('parent_id')
            ->where('id', '!=', $id)
            ->pluck('content', 'id')->toArray();

        return view('elearning::admin.comment.edit', compact('item', 'users', 'lessons', 'parentComments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\CommentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommentRequest $request, $id)
    {
        $this->commentRepository->updateById($request->all(), $id);

        return redirect()
            ->route('elearning.admin.comments.edit', [
                'comment' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::comment.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->commentRepository->delete($id);

        return redirect()
            ->route('elearning.admin.comments.index')
            ->with('success', __('elearning::comment.notification.deleted'));
    }
}
