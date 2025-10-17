<?php

namespace Newnet\Acl\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Acl\Models\Admin;
use Newnet\Acl\Repositories\AdminRepositoryInterface;

class AdminController extends Controller
{
    /**
     * @var AdminRepositoryInterface
     */
    private $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function detail($id, Request $request)
    {
        /** @var Admin $admin */
        $admin = $this->adminRepository->find($id);

        return view('cms::web.admin.detail', compact('admin'));
    }
}
