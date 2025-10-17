<?php

namespace Newnet\Seo\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Newnet\Seo\Http\Requests\ErrorRedirectRequest;
use Newnet\Seo\Repositories\ErrorRedirectRepositoryInterface;
use Newnet\Seo\SeoAdminMenuKey;
use Newnet\AdminUi\Facades\AdminMenu;

class ErrorRedirectController extends Controller
{
    /**
     * @var ErrorRedirectRepositoryInterface
     */
    protected $errorRedirectRepository;

    public function __construct(ErrorRedirectRepositoryInterface $errorRedirectRepository)
    {
        $this->errorRedirectRepository = $errorRedirectRepository;
    }

    public function index(Request $request)
    {
        $items = $this->errorRedirectRepository->paginate($request->input('max', 20));

        return view('seo::admin.error-redirect.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ERROR_REDIRECT);

        return view('seo::admin.error-redirect.create');
    }

    public function store(ErrorRedirectRequest $request)
    {
        $item = $this->errorRedirectRepository->create($request->all());

        return redirect()
            ->route('seo.admin.error-redirect.edit', $item)
            ->with('success', __('seo::error-redirect.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(SeoAdminMenuKey::ERROR_REDIRECT);

        $item = $this->errorRedirectRepository->find($id);

        return view('seo::admin.error-redirect.edit', compact('item'));
    }

    public function update(ErrorRedirectRequest $request, $id)
    {
        $this->errorRedirectRepository->updateById($request->all(), $id);

        return back()->with('success', __('seo::error-redirect.notification.updated'));
    }

    public function destroy($id, Request $request)
    {
        $redirect = $this->errorRedirectRepository->find($id);

        if (!$redirect) {
            abort(404, 'Redirect not found');
        }

        $this->errorRedirectRepository->delete($id);

        Cache::forget('redirect_rule:' . $redirect->from_path);

        if ($request->wantsJson()) {
            Session::flash('success', __('seo::error-redirect.notification.deleted'));
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('seo.admin.error-redirect.index')
            ->with('success', __('seo::error-redirect.notification.deleted'));
    }
}
