<?php

namespace Newnet\Core\Console\Commands;

use Illuminate\Console\Command;

class UpgradeCommand extends Command
{
    protected $name = 'newnet:upgrade';

    protected $description = 'Upgrade core';

    public function handle()
    {
        $this->info('Upgrading...');

        if ($this->option('verbose')) {
            $this->call('vendor:publish', [
                '--tag'   => 'newnet-admin',
                '--force' => true,
            ]);

            $this->call('vendor:publish', [
                '--tag'   => 'module-assets',
                '--force' => true,
            ]);

            $this->call('vendor:publish', [
                '--tag'   => 'newnet-assets',
                '--force' => true,
            ]);
        } else {
            $this->callSilent('vendor:publish', [
                '--tag'   => 'newnet-admin',
                '--force' => true,
            ]);

            $this->callSilent('vendor:publish', [
                '--tag'   => 'module-assets',
                '--force' => true,
            ]);

            $this->callSilent('vendor:publish', [
                '--tag'   => 'newnet-assets',
                '--force' => true,
            ]);
        }

        $this->info('System Successfully Upgraded');
    }
}
