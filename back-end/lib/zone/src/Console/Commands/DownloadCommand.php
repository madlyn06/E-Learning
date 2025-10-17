<?php

namespace Newnet\Zone\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Newnet\Zone\Downloader;
use Newnet\Zone\Imports\ZoneImport;
use Maatwebsite\Excel\Facades\Excel;

class DownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newnet:zone:download {--province_false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Module Zone Download Data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Downloading...');

        $tmpFile = app(Downloader::class)->downloadFile();

        $this->info('Importing...');
        $province_status = !$this->option('province_false');
        Excel::import(new ZoneImport($province_status), $tmpFile);

        File::delete($tmpFile);

        $this->info('Completed');
    }
}
