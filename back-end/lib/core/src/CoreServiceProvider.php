<?php

namespace Newnet\Core;

use Illuminate\Support\ServiceProvider;
use Newnet\Core\Console\Commands\InstallCommand;
use Newnet\Core\Console\Commands\UpgradeCommand;

class CoreServiceProvider extends ServiceProvider
{
    protected $commands = [
        UpgradeCommand::class,
    ];

    protected $commandsCli = [
        InstallCommand::class,
    ];

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/core.php', 'core');

        $coreConfigData = include __DIR__ . '/../config/core.php';
        $this->app['config']->set('app.debug_blacklist', $coreConfigData['debug_blacklist']);
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/core.php', 'core');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/core.php' => config_path('core.php'),
        ], 'core-config');

        $this->registerCommands();
    }

    protected function registerCommands()
    {
        $this->commands($this->commands);

        if ($this->app->runningInConsole()) {
            $this->commands($this->commandsCli);
        }
    }
}
