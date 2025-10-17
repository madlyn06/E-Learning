<?php

namespace Newnet\Theme\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class ThemeLinkCommand extends Command
{
    protected $name = 'cms:theme.link';

    protected $description = 'Link theme asset into public for developemnt';

    public function handle()
    {
        $pathsToPublish = ServiceProvider::pathsToPublish(null, 'theme');
        foreach ($pathsToPublish as $from => $to) {
            if (!File::exists($to)) {
                $this->createParentDirectory(dirname($to));

                File::link($from, $to);

                $this->info($from . ' <=> ' . $to);
            } else {
                $this->error('Target is exists: ' . $to);
            }
        }
    }

    protected function createParentDirectory($directory)
    {
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
    }
}
