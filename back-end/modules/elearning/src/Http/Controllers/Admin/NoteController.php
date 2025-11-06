<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\NoteRequest;
use Modules\Elearning\Models\Lesson;
use Modules\Elearning\Models\User;
use Modules\Elearning\Repositories\NoteRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class NoteController extends Controller
{
    protected NoteRepository $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::NOTE);
        
        $items = $this->noteRepository->paginate($request->input('max', 20));

        return view('elearning::admin.note.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::NOTE);

        $users = User::pluck('name', 'id')->toArray();
        $lessons = Lesson::pluck('title', 'id')->toArray();

        return view('elearning::admin.note.create', compact('users', 'lessons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\NoteRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NoteRequest $request)
    {
        $item = $this->noteRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.notes.edit', [
                'note' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::note.notification.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::NOTE);

        $item = $this->noteRepository->find($id);

        return view('elearning::admin.note.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::NOTE);

        $item = $this->noteRepository->find($id);
        $users = User::pluck('name', 'id')->toArray();
        $lessons = Lesson::pluck('title', 'id')->toArray();

        return view('elearning::admin.note.edit', compact('item', 'users', 'lessons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Modules\Elearning\Http\Requests\NoteRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NoteRequest $request, $id)
    {
        $this->noteRepository->updateById($request->all(), $id);

        return redirect()
            ->route('elearning.admin.notes.edit', [
                'note' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::note.notification.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->noteRepository->delete($id);

        return redirect()
            ->route('elearning.admin.notes.index')
            ->with('success', __('elearning::note.notification.deleted'));
    }
}
