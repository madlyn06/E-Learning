<?php

namespace Modules\Manage\Http\Controllers\Admin;

use AdminMenu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\FileEvent;
use Modules\Manage\Http\Requests\FileRequest;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Repositories\FileRepository;
use Modules\Manage\Services\FileService;

class DocumentController extends Controller
{
    protected FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function index(Request $request)
    {
        $items = $this->fileRepository->paginate($request->input('max', 20));

        return view('manage::admin.document.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FILE_ALL);

        return view('manage::admin.document.create');
    }

    public function store(FileRequest $request)
    {
        $item = $this->fileRepository->create($request->all());
        event(new FileEvent('created'));
        return redirect()
            ->route('manage.admin.documents.edit', [
                'document' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::document.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FILE_ALL);

        $item = $this->fileRepository->find($id);

        return view('manage::admin.document.edit', compact('item'));
    }

    public function update(FileRequest $request, $id)
    {
        $data = $request->all();
        if (empty($data['file_categories'])) {
            $data['file_categories'] = [];
        }
        $this->fileRepository->updateById($data, $id);
        event(new FileEvent('updated'));

        return back()->with('success', __('manage::document.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->fileRepository->delete($id);
        event(new FileEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::document.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.documents.index')
            ->with('success', __('manage::document.notification.deleted'));
    }
}
