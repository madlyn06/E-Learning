<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Newnet\Core\Services\Installer\SetupScript;

class SetAppKey implements SetupScript
{
    /**
     * Fire the install script
     * @param Command $command
     */
    public function fire(Command $command)
    {
        if ($command->option('verbose')) {
            $command->call('key:generate');
        } else {
            $command->callSilent('key:generate');
            $command->info('Key generated successfully');
        }
    }
}
