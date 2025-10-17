<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Newnet\Core\Services\Installer\SetupScript;

class RunMigrator implements SetupScript
{
    /**
     * Fire the install script
     * @param  Command $command
     */
    public function fire(Command $command)
    {
        if (Schema::hasTable('migrations')) {
            if ($command->confirm('Database exists. Do you want drop all?')) {
                $command->info('Drop all tables...');
                $command->callSilent('db:wipe');

                if ($command->option('verbose')) {
                    $command->info('Dropped all tables successfully.');
                }
            }
        }

        $command->info('Install Database...');

        if ($command->option('verbose')) {
            $command->call('migrate');
        } else {
            $command->callSilent('migrate');
        }

        $command->info('Database install successfully');
    }
}
