<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\Seo\Http\Requests\AdsRequest;
use Newnet\Seo\Models\Ads;
use Newnet\Seo\Repositories\AdsRepositoryInterface;
use Newnet\Seo\Repositories\Eloquent\AdsRepository;
use Newnet\AdminUi\Facades\AdminMenu;
use Newnet\Seo\Events\AdsEvent;

class AdsController extends Controller
{
    /**
     * @var AdsRepositoryInterface|AdsRepository
     */
    private $adsRepository;

    public function __construct(AdsRepositoryInterface $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    public function index(Request $request)
    {
        $items = $this->adsRepository->paginate($request->input('max', 20));

        return view('seo::admin.ads.index', compact('items'));
    }

    public function create(Request $request)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ADS);

        $item = new Ads();
        $item->is_active = true;

        return view('seo::admin.ads.create', compact('item'));
    }

    public function store(AdsRequest $request)
    {
        $data = $request->all();
        $data['hashed'] = Hash::make($request->code);
        $item = $this->adsRepository->create($data);

        event(new AdsEvent('created'));

        return redirect()
            ->route('seo.admin.ads.edit', [
                $item->id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('seo::ads.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ADS);

        $item = $this->adsRepository->find($id);

        return view('seo::admin.ads.edit', compact('item'));
    }

    public function update($id, AdsRequest $request)
    {
        $data = $request->all();
        $data['hashed'] = Hash::make($request->code);

        $this->adsRepository->updateById($data, $id);

        event(new AdsEvent('updated'));

        return back()->with('success', __('seo::ads.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $this->adsRepository->delete($id);

        event(new AdsEvent('deleted'));

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::ads.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.ads.index')
            ->with('success', __('seo::ads.notification.deleted'));
    }

    public function destroyMultipleItems(Request $request)
    {
        $ids = $request->ids;
        Ads::whereIn('id', $ids)->delete();

        event(new AdsEvent('deleted'));
        Session::flash('success', __('seo::ads.notification.deleted'));
        return response()->json(['success' => 200]);
    }
}
