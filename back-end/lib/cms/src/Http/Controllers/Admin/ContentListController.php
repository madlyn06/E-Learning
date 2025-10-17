<?php

namespace Newnet\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Newnet\Cms\CmsAdminMenuKey;
use Newnet\Cms\Models\Post;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Cms\Events\ContentListableEvent;
use Newnet\Cms\Events\PostEvent;
use Newnet\Cms\Models\ContentList;
use Newnet\Cms\Utils\EloquentUtils;

class ContentListController extends Controller
{
    public function create()
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $item = new Post();

        return view('cms::admin.content-list.create', compact('item'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(CmsAdminMenuKey::POST);

        $item = ContentList::find($id);
        $post = $item->post;

        return view('cms::admin.content-list.edit', compact('item', 'post'));
    }

    public function update($id, Request $request)
    {
        $item = ContentList::find($id);
        $item->update($request->all());
        event(new PostEvent('updated', $id, auth('admin')->user()->id));
        return back()->with('success', __('cms::content-list.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        ContentList::destroy($id);
        event(new PostEvent('deleted', $id, auth('admin')->user()->id));
        if ($request->wantsJson()) {
            Session::flash('success', __('cms::content-list.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('cms.admin.content-list.index')
            ->with('success', __('cms::content-list.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        ContentList::whereIn('id', $ids)->delete();
        event(new PostEvent('deleted', $ids, auth('admin')->user()->id));
        Session::flash('success', __('cms::content-list.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
