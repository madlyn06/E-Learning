<?php

namespace Newnet\Dashboard\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Newnet\Dashboard\Facades\Dashboard;
use Newnet\Dashboard\Repositories\DashboardRepositoryInterface;
use Newnet\Dashboard\Repositories\Eloquent\DashboardRepository;

class DashboardController extends Controller
{
    /**
     * @var DashboardRepositoryInterface|DashboardRepository
     */
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        $myItems = $this->dashboardRepository->myDashboard()->pluck('class_name')->toArray();
        $myItems = $myItems ?: Dashboard::onlyCanAccess()->keys()->toArray();

        $myDashboard = Dashboard::only($myItems);

        return view('dashboard::admin.index', compact('myDashboard'));
    }

    public function setting()
    {
        $myDashboard = Dashboard::only($this->dashboardRepository->myDashboard()->pluck('class_name')->toArray());
        $allDashboard = Dashboard::onlyCanAccess()->except($myDashboard->keys());

        return view('dashboard::admin.setting', compact('myDashboard', 'allDashboard'));
    }

    public function save(Request $request)
    {
        $data = $request->input('items.*.id', []);
        $this->dashboardRepository->syncMyDashboard($data);

        return [
            'success' => true,
            'message' => __('dashboard::message.notification.updated')
        ];
    }
}
