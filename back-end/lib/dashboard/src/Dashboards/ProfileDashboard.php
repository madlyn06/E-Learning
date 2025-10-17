<?php

namespace Newnet\Dashboard\Dashboards;

use Newnet\Dashboard\DashboardItem;

class ProfileDashboard extends DashboardItem
{
    public function col()
    {
        return 4;
    }

    public function name()
    {
        return __('dashboard::dashboard.profile.name');
    }

    public function toHtml()
    {
        return view('dashboard::dashboards.profile');
    }
}
