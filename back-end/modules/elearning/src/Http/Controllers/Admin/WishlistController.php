<?php

namespace Modules\Elearning\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Elearning\ElearningAdminMenuKey;
use Modules\Elearning\Http\Requests\WishlistRequest;
use Modules\Elearning\Models\Course;
use Modules\Elearning\Models\User;
use Modules\Elearning\Repositories\WishlistRepository;
use Newnet\AdminUi\Facades\AdminMenu;

class WishlistController extends Controller
{
    protected WishlistRepository $wishlistRepository;

    public function __construct(WishlistRepository $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function index(Request $request)
    {
        $items = $this->wishlistRepository->paginate($request->input('max', 20));

        return view('elearning::admin.wishlist.index', compact('items'));
    }

    public function create()
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::WISHLIST);

        $users = User::pluck('name', 'id')->toArray();
        $courses = Course::pluck('name', 'id')->toArray();

        return view('elearning::admin.wishlist.create', compact('users', 'courses'));
    }

    public function store(WishlistRequest $request)
    {
        $item = $this->wishlistRepository->create($request->all());

        return redirect()
            ->route('elearning.admin.wishlist.edit', [
                'wishlist' => $item,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::wishlist.notification.created'));
    }

    public function edit($id)
    {
        AdminMenu::activeMenu(ElearningAdminMenuKey::WISHLIST);

        $item = $this->wishlistRepository->find($id);
        $users = User::pluck('name', 'id')->toArray();
        $courses = Course::pluck('name', 'id')->toArray();

        return view('elearning::admin.wishlist.edit', compact('item', 'users', 'courses'));
    }

    public function update(WishlistRequest $request, $id)
    {
        $this->wishlistRepository->update($id, $request->all());

        if ($request->input('_action') == 'save_and_exit') {
            return redirect()
                ->route('elearning.admin.wishlist.index')
                ->with('success', __('elearning::wishlist.notification.updated'));
        }

        return redirect()
            ->route('elearning.admin.wishlist.edit', [
                'wishlist' => $id,
                'edit_locale' => $request->input('edit_locale'),
            ])
            ->with('success', __('elearning::wishlist.notification.updated'));
    }

    public function destroy($id)
    {
        $this->wishlistRepository->delete($id);

        return redirect()
            ->route('elearning.admin.wishlist.index')
            ->with('success', __('elearning::wishlist.notification.deleted'));
    }
}
