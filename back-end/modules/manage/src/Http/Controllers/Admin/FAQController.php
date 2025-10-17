<?php

namespace Modules\Manage\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Manage\Events\FAQEvent;
use Modules\Manage\ManageAdminMenuKey;
use Modules\Manage\Http\Requests\FAQRequest;
use Modules\Manage\Repositories\FAQRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class FAQController extends Controller
{
    protected FAQRepository $fAQRepository;

    public function __construct(FAQRepository $fAQRepository)
    {
        $this->fAQRepository = $fAQRepository;
    }

    public function index(Request $request)
    {
        $items = $this->fAQRepository->paginate($request->input('max', 20));

        return view('manage::admin.faq.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FAQ);

        return view('manage::admin.faq.create');
    }

    public function store(FAQRequest $request)
    {
        $item = $this->fAQRepository->create($request->all());
        event(new FAQEvent('created'));

        return redirect()
            ->route('manage.admin.faq.edit', [
                'faq' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('manage::faq.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ManageAdminMenuKey::FAQ);

        $item = $this->fAQRepository->find($id);

        return view('manage::admin.faq.edit', compact('item'));
    }

    public function update(FAQRequest $request, $id)
    {
        $this->fAQRepository->updateById($request->all(), $id);
        event(new FAQEvent('updated'));
        return back()->with('success', __('manage::faq.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->fAQRepository->delete($id);
        event(new FAQEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('manage::faq.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('manage.admin.faq.index')
            ->with('success', __('manage::faq.notification.deleted'));
    }
}
