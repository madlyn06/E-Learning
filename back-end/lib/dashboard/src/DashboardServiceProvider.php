<?php

namespace Newnet\Dashboard;

use Newnet\Dashboard\Dashboards\ProfileDashboard;
use Newnet\Dashboard\Repositories\DashboardRepositoryInterface;
use Newnet\Dashboard\Repositories\Eloquent\DashboardRepository;
use Newnet\Dashboard\Dashboards\WelcomeDashboard;
use Newnet\Dashboard\Models\Dashboard;
use Newnet\Module\Support\BaseModuleServiceProvider;

class DashboardServiceProvider extends BaseModuleServiceProvider
{
    const MODULE_NAMESPACE = 'dashboard';

    public function getModuleNamespace()
    {
        return self::MODULE_NAMESPACE;
    }

    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(__DIR__.'/../config/dashboard.php', 'dashboard');

        $this->app->singleton('dashboard', function () {
            return new DashboardManager();
        });
    }

    public function boot()
    {
        parent::boot();

        $this->app->singleton(DashboardRepositoryInterface::class, function () {
            return new DashboardRepository(new Dashboard());
        });
    }

    protected function registerDashboards()
    {
        if (config('dashboard.show_default_dashboard')) {
            \Dashboard::push(WelcomeDashboard::class);
            \Dashboard::push(ProfileDashboard::class);
        }
    }

    public function provides()
    {
        return [
            'dashboard',
        ];
    }
}
