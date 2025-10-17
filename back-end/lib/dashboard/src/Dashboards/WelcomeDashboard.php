<?php

namespace Newnet\Dashboard\Dashboards;

use Newnet\Dashboard\DashboardItem;

class WelcomeDashboard extends DashboardItem
{
    public function col()
    {
        return 4;
    }

    public function name()
    {
        return __('dashboard::dashboard.welcome.name');
    }

    public function toHtml()
    {
        return view('dashboard::dashboards.welcome');
    }
}
