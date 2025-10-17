<?php

namespace Newnet\Zone\Console\Commands;

use Illuminate\Console\Command;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newnet:zone:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Module Zone Update Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating...');
    }
}
