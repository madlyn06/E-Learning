<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Newnet\Core\Services\Installer\SetupScript;

class ProtectInstaller implements SetupScript
{
    /**
     * @var Filesystem
     */
    protected $finder;

    /**
     * @param Filesystem $finder
     */
    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Fire the install script
     * @param Command $command
     * @throws Exception
     */
    public function fire(Command $command)
    {
        if ($this->finder->isFile('.env') && !$command->option('force')) {
            throw new Exception('Newnet has already been installed.');
        }
    }
}
