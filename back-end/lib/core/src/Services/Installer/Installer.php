<?php

namespace Newnet\Core\Services\Installer;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

class Installer
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $scripts = [];

    /**
     * @var Filesystem
     */
    protected $finder;

    /**
     * @param Filesystem $finder
     * @param Application $app
     */
    public function __construct(Application $app, Filesystem $finder)
    {
        $this->finder = $finder;
        $this->app = $app;
    }

    /**
     * @param array $scripts
     * @return $this
     */
    public function stack(array $scripts)
    {
        $this->scripts = $scripts;

        return $this;
    }

    /**
     * Fire install scripts
     * @param Command $command
     * @return bool
     */
    public function install(Command $command)
    {
        foreach ($this->scripts as $script) {
            try {
                $this->app->make($script)->fire($command);
            } catch (\Exception $e) {
                $command->error($e->getMessage());

                return false;
            }
        }

        return true;
    }
}
