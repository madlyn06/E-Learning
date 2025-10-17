<?php

namespace Newnet\Core\Services\Installer;

use Illuminate\Console\Command;

interface SetupScript
{
    /**
     * Fire the install script
     * @param  Command $command
     * @return mixed
     */
    public function fire(Command $command);
}
