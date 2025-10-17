<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Newnet\Core\Services\Installer\EnvFileWriter;
use Newnet\Core\Services\Installer\SetupScript;

class CreateEnvFile implements SetupScript
{
    /**
     * @var EnvFileWriter
     */
    protected $env;

    /**
     * @param EnvFileWriter $env
     */
    public function __construct(EnvFileWriter $env)
    {
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

        $this->env->create();

        $command->info('Successfully created .env file');
    }
}
