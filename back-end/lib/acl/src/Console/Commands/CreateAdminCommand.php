<?php

namespace Newnet\Acl\Console\Commands;

use Illuminate\Console\Command;
use Newnet\Core\Services\Installer\Installer;
use Symfony\Component\Console\Input\InputOption;

class CreateAdminCommand extends Command
{
    protected $name = 'cms:create-admin';

    protected $description = 'Create admin account';

    /**
     * @var Installer
     */
    private $installer;

    public function __construct(Installer $installer)
    {
        parent::__construct();

        $this->installer = $installer;
    }

    public function handle()
    {
        $this->installer
            ->stack([
                \Newnet\Core\Services\Installer\Scripts\CreateAdminAccount::class,
            ])
            ->install($this);
    }

    protected function getOptions()
    {
        return [
            ['name', 'N', InputOption::VALUE_OPTIONAL, 'Your Name'],
            ['email', 'E', InputOption::VALUE_OPTIONAL, 'Your Email'],
            ['password', 'P', InputOption::VALUE_OPTIONAL, 'Password'],
        ];
    }
}
