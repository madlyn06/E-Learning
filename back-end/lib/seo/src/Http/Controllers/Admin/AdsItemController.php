<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\Seo\Http\Requests\AdsItemRequest;
use Newnet\Seo\Repositories\AdsItemRepositoryInterface;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\Events\AdsEvent;
use Newnet\Seo\Models\AdsItem;
use Newnet\Seo\Repositories\AdsRepositoryInterface;

class AdsItemController extends Controller
{

    public function __construct(
        private AdsRepositoryInterface $adsRepository,
        private AdsItemRepositoryInterface $adsItemRepository
    )
    {
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ADS);

        $item = $this->adsRepository->find($request->adsId);

        return view('seo::admin.ads-item.create', compact('item'));
    }

    public function store(AdsItemRequest $request)
    {
        $data = $request->all();
        $item = $this->adsItemRepository->create($data);

        event(new AdsEvent('created'));

        return redirect()
            ->route('seo.admin.ads-items.edit', [
                $item->id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('seo::ads.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ADS);

        $item = $this->adsItemRepository->find($id);

        return view('seo::admin.ads-item.edit', compact('item'));
    }

    public function update($id, AdsItemRequest $request)
    {
        $data = $request->all();

        $this->adsItemRepository->updateById($data, $id);

        event(new AdsEvent('updated'));

        return back()->with('success', __('seo::ads.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->adsItemRepository->delete($id);

        event(new AdsEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::ads.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.ads-items.index')
            ->with('success', __('seo::ads.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        AdsItem::whereIn('id', $ids)->delete();

        event(new AdsEvent('deleted'));
        Session::flash('success', __('seo::ads.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
