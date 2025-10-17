<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\SectionRequest;
use Modules\Elearning\Repositories\SectionRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class SectionController extends Controller
{
    protected SectionRepository $sectionRepository;

    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function index(Request $request)
    {
        $items = $this->sectionRepository->paginate($request->input('max', 20));

        return view('elearning::admin.section.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::SECTION);

        return view('elearning::admin.section.create');
    }

    public function store(SectionRequest $request)
    {
        $item = $this->sectionRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.section.edit', [
                'section' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::section.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::SECTION);

        $item = $this->sectionRepository->find($id);

        return view('elearning::admin.section.edit', compact('item'));
    }

    public function update(SectionRequest $request, $id)
    {
        $this->sectionRepository->updateById($request->all(), $id);

        return back()->with('success', __('elearning::section.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->sectionRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('elearning::section.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('elearning.admin.section.index')
            ->with('success', __('elearning::section.notification.deleted'));
    }
}
