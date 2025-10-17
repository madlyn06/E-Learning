<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Newnet\Core\Services\Installer\EnvFileWriter;
use Newnet\Core\Services\Installer\SetupScript;

class ConfigureDefaultValue implements SetupScript
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var EnvFileWriter
     */
    protected $env;

    /**
     * @param Config $config
     * @param EnvFileWriter $env
     */
    public function __construct(Config $config, EnvFileWriter $env)
    {
        $this->config = $config;
        $this->env = $env;
    }

    /**
     * @var Command
     */
    protected $command;

    /**
     * Fire the install script
     * @param Command $command
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $vars = [];

        $vars['app_name'] = 'Newnet';

        $this->env->write($vars);
    }
}
