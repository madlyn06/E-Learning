<?php

namespace Newnet\Acl;

use Event;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Newnet\Acl\Console\Commands\CreateAdminCommand;
use Newnet\Acl\Contracts\PermissionManagerInterface;
use Newnet\Acl\Events\AdminEvent;
use Newnet\Acl\Http\Middleware\AdminAcl;
use Newnet\Acl\Http\Middleware\AdminAuth;
use Newnet\Acl\Http\Middleware\AdminLocaleMiddleware;
use Newnet\Acl\Http\Middleware\AdminPermission;
use Newnet\Acl\Http\Middleware\RedirectIfAdminAuth;
use Newnet\Acl\Listeners\AdminListener;
use Newnet\Acl\Models\Role;
use Newnet\Acl\Models\Admin;
use Newnet\Acl\Repositories\RoleRepository;
use Newnet\Acl\Repositories\RoleRepositoryInterface;
use Newnet\Acl\Repositories\AdminRepository;
use Newnet\Acl\Repositories\AdminRepositoryInterface;
use Newnet\Module\Support\BaseModuleServiceProvider;

class AclServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerMiddleware();
        $this->registerConfigData();

        $this->app->singleton(PermissionManagerInterface::class, PermissionManager::class);

        $this->app->singleton(RoleRepositoryInterface::class, function () {
            return new RoleRepository(new Role);
        });

        $this->app->singleton(AdminRepositoryInterface::class, function () {
            return new AdminRepository(new Admin);
        });
    }

    public function boot()
    {
        parent::boot();

        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');
        $this->registerBladeDirectives();

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateAdminCommand::class,
            ]);
        }

        Event::listen(AdminEvent::class, AdminListener::class);
    }

    protected function registerConfigData()
    {
        $aclConfigData = include __DIR__ . '/../config/acl.php';
        $authConfig = $this->app['config']->get('auth');
        $auth = array_merge_recursive_distinct($aclConfigData['auth'], $authConfig);
        $this->app['config']->set('auth', $auth);
    }

    protected function registerMiddleware()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('admin.auth', AdminAuth::class);
        $router->aliasMiddleware('admin.can', AdminPermission::class);
        $router->aliasMiddleware('admin.guest', RedirectIfAdminAuth::class);
        $router->aliasMiddleware('admin.acl', AdminAcl::class);
        $router->aliasMiddleware('admin.locale', AdminLocaleMiddleware::class);
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('admincan', function ($expression) {
            return "<?php if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endadmincan', function () {
            return "<?php endif; ?>";
        });
    }
}
