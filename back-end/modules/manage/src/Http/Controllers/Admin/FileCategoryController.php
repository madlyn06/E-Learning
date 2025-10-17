<?php

namespace Modules\Manage\Http\Controllers\Admin;

use AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\FileCategoryEvent;
use Modules\Manage\Http\Requests\FileCategoryRequest;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Models\FileCategory;
use Modules\Manage\Repositories\FileCategoryRepository;

class FileCategoryController extends Controller
{
    protected FileCategoryRepository $fileCategoryRepository;

    public function __construct(FileCategoryRepository $fileCategoryRepository)
    {
        $this->fileCategoryRepository = $fileCategoryRepository;
    }

    public function index(Request $request)
    {
        $items = $this->fileCategoryRepository->paginate($request->input('max', 20));

        return view('manage::admin.file-category.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FILE_CATEGORY);
        $item = new FileCategory();
        $item->is_active = true;
        $item->parent_id = $request->input('parent_id');

        return view('manage::admin.file-category.create', compact('item'));
    }

    public function store(FileCategoryRequest $request)
    {
        $item = $this->fileCategoryRepository->create($request->all());
        event(new FileCategoryEvent('created'));
        return redirect()
            ->route('manage.admin.file-categories.edit', [
                'file_category' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::file-category.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FILE_CATEGORY);

        $item = $this->fileCategoryRepository->find($id);

        return view('manage::admin.file-category.edit', compact('item'));
    }

    public function update(FileCategoryRequest $request, $id)
    {
        $this->fileCategoryRepository->updateById($request->all(), $id);
        event(new FileCategoryEvent('updated'));

        return back()->with('success', __('manage::file-category.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->fileCategoryRepository->delete($id);
        event(new FileCategoryEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::file-category.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.file-categories.index')
            ->with('success', __('manage::file-category.notification.deleted'));
    }
}
