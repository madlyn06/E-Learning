<?php

namespace Modules\StaticBlock\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\StaticBlock\StaticBlockAdminMenuKey;
use Modules\StaticBlock\Http\Requests\StaticBlockRequest;
use Modules\StaticBlock\Repositories\StaticBlockRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Illuminate\Support\Str;

class StaticBlockController extends Controller
{
    protected StaticBlockRepository $staticBlockRepository;

    public function __construct(StaticBlockRepository $staticBlockRepository)
    {
        $this->staticBlockRepository = $staticBlockRepository;
    }

    public function index(Request $request)
    {
        $items = $this->staticBlockRepository->paginate($request->input('max', 20));

        return view('staticblock::admin.static-block.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(StaticBlockAdminMenuKey::STATIC_BLOCK);

        return view('staticblock::admin.static-block.create');
    }

    public function store(StaticBlockRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $item = $this->staticBlockRepository->create($data);

        return redirect()
            ->route('staticblock.admin.static-block.edit', [
                'static_block' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('staticblock::static-block.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(StaticBlockAdminMenuKey::STATIC_BLOCK);

        $item = $this->staticBlockRepository->find($id);

        return view('staticblock::admin.static-block.edit', compact('item'));
    }

    public function update(StaticBlockRequest $request, $id)
    {
        $this->staticBlockRepository->updateById($request->all(), $id);

        return back()->with('success', __('staticblock::static-block.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->staticBlockRepository->delete($id);

        if ($request->wantsJson()) {
            Session::flash('success', __('staticblock::static-block.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('staticblock.admin.static-block.index')
            ->with('success', __('staticblock::static-block.notification.deleted'));
    }
}
