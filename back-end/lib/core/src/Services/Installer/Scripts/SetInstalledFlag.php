<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Newnet\Core\Services\Installer\EnvFileWriter;
use Newnet\Core\Services\Installer\SetupScript;

class SetInstalledFlag implements SetupScript
{
    /**
     * @var EnvFileWriter
     */
    protected $env;

    public function __construct(EnvFileWriter $env)
    {
        $this->env = $env;
    }

    /**
     * Fire the install script
     * @param Command $command
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Command $command)
    {
        $vars = [];

        $vars['installed'] = 'true';

        $this->env->write($vars);

        $command->info('The application is now installed');
    }
}
