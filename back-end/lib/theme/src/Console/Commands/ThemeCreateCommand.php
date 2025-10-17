<?php

namespace Newnet\Theme\Console\Commands;

use Illuminate\Console\Command;
use Newnet\Theme\Generators\ThemeGenerator;

class ThemeCreateCommand extends Command
{
    protected $signature = 'cms:theme.create {name?} {--dev}';

    protected $description = 'Create a new theme';

    public function handle()
    {
        $name = $this->askForName();
        $isDev = $this->option('dev');

        app(ThemeGenerator::class)
            ->setThemeName($name)
            ->setConsole($this)
            ->setIsDev($isDev)
            ->generate();
    }

    protected function askForName()
    {
        if ($name = $this->argument('name')) {
            return $name;
        }

        do {
            $name = $this->ask('Enter Theme Name');
            if ($name == '') {
                $this->error('Name is required');
            }
        } while (!$name);

        return $name;
    }
}
