<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Newnet\Core\Services\Installer\SetupScript;

class PublishAssets implements SetupScript
{
    /**
     * Fire the install script
     * @param  Command $command
     */
    public function fire(Command $command)
    {
        $command->info('Publish assets...');

        if ($command->option('verbose')) {
            $command->call('vendor:publish', [
                '--tag' => 'newnet-admin',
                '--force',
            ]);

            $command->call('vendor:publish', [
                '--tag' => 'newnet-assets',
                '--force',
            ]);

            $command->call('vendor:publish', [
                '--tag' => 'module-assets',
                '--force',
            ]);
        } else {
            $command->callSilent('vendor:publish', [
                '--tag' => 'newnet-admin',
                '--force',
            ]);

            $command->callSilent('vendor:publish', [
                '--tag' => 'newnet-assets',
                '--force',
            ]);

            $command->callSilent('vendor:publish', [
                '--tag' => 'module-assets',
                '--force',
            ]);
        }
    }
}
