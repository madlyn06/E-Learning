<?php

namespace Newnet\Acl\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Newnet\Acl\Http\Requests\ProfileRequest;
use Newnet\Acl\Models\Admin;
use Newnet\Acl\Repositories\AdminRepositoryInterface;

class ProfileController extends Controller
{
    /**
     * @var AdminRepositoryInterface
     */
    private $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        $user = current_admin();

        return view('acl::admin.profile.index', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        /** @var Admin $user */
        $user = $this->adminRepository->update(current_admin()->id, $request->all());

        if ($avatar = $request->input('avatar')) {
            $oldAvatar = $user->getFirstMedia('avatar');
            if ($oldAvatar && $oldAvatar->id != $avatar) {
                $oldAvatar->delete();
            }
            $user->attachMedia($avatar, 'avatar');
        }

        return redirect()->back()->with('success', __('acl::profile.notification.updated'));
    }

    public function language($locale)
    {
        $currentAdmin = get_current_admin();

        $settings = $currentAdmin->settings;
        $settings['admin_locale'] = $locale;

        $currentAdmin->update([
            'settings' => $settings
        ]);

        return back();
    }
}
