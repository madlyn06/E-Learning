<?php

namespace Newnet\Customer;

use Illuminate\Routing\Router;
use Newnet\Customer\Http\Middleware\Authenticate;
use Newnet\Customer\Http\Middleware\RedirectIfAuthenticated;
use Newnet\Customer\Models\Group;
use Newnet\Customer\Repositories\Eloquent\GroupRepository;
use Newnet\Customer\Repositories\GroupRepositoryInterface;
use Newnet\Module\Support\BaseModuleServiceProvider;
use Newnet\Customer\Repositories\Eloquent\CustomerRepository;
use Newnet\Customer\Repositories\CustomerRepositoryInterface;
use Newnet\Customer\Models\Customer;

class CustomerServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerConfigData();

        $this->app->singleton(CustomerRepositoryInterface::class, function () {
            return new CustomerRepository(new Customer());
        });

        $this->app->singleton(GroupRepositoryInterface::class, function () {
            return new GroupRepository(new Group());
        });

    }

    public function boot()
    {
        parent::boot();

        $this->overwriteMiddleware();
    }

    protected function registerConfigData()
    {
        $aclConfigData = include __DIR__ . '/../config/customer.php';
        $authConfig = $this->app['config']->get('auth');
        $auth = array_merge_recursive_distinct($aclConfigData['auth'], $authConfig);
        $this->app['config']->set('auth', $auth);
    }

    protected function overwriteMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
    }

}
