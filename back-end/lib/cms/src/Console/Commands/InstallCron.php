<?php

namespace Newnet\Cms\Console\Commands;

use Illuminate\Console\Command;

class InstallCron extends Command
{
    protected $signature = 'cron:install';
    protected $description = 'Install cronjob from configuaration';

    public function handle()
    {
        $cronFile = base_path('cronjobs.txt');

        if (!file_exists($cronFile)) {
            $this->error("File $cronFile not found");
            return;
        }

        $existing = shell_exec('crontab -l 2>/dev/null');
        $filtered = collect(explode("\n", $existing))
            ->filter(fn($line) => !str_contains($line, base_path()))
            ->implode("\n");

        $newCron = file_get_contents($cronFile);
        $final = $filtered . "\n" . $newCron . "\n";

        file_put_contents('/tmp/laravel_cron', $final);
        exec('crontab /tmp/laravel_cron');
        unlink('/tmp/laravel_cron');

        $this->info("Install cronjob successfully!");
    }
}
