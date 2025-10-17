<?php

namespace Newnet\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Newnet\Core\Events\NewnetInstalled;
use Newnet\Core\Services\Installer\Installer;
use Newnet\Core\Services\Installer\Traits\BlockMessage;
use Newnet\Core\Services\Installer\Traits\SectionMessage;
use Symfony\Component\Console\Input\InputOption;

class InstallCommand extends Command
{
    use BlockMessage, SectionMessage;

    protected $name = 'newnet:install';

    protected $description = 'Install Newnet';

    /**
     * @var Installer
     */
    protected $installer;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Installer $installer, Config $config)
    {
        parent::__construct();

        $this->installer = $installer;
        $this->config = $config;
    }

    public function handle()
    {
        $this->getLaravel()['env'] = 'local';

        $this->blockMessage(
            'Welcome!',
            sprintf('Install Newnet Version: %s', \Newnet::version()),
            'comment'
        );

        $success = $this->installer
            ->stack([
                \Newnet\Core\Services\Installer\Scripts\ProtectInstaller::class,
                \Newnet\Core\Services\Installer\Scripts\CreateEnvFile::class,
                \Newnet\Core\Services\Installer\Scripts\SetAppKey::class,
                \Newnet\Core\Services\Installer\Scripts\ConfigureDefaultValue::class,
                \Newnet\Core\Services\Installer\Scripts\ConfigureDatabase::class,
                \Newnet\Core\Services\Installer\Scripts\RunMigrator::class,
                \Newnet\Core\Services\Installer\Scripts\ConfigureAppUrl::class,
                \Newnet\Core\Services\Installer\Scripts\ConfigureAdminPrefix::class,
                \Newnet\Core\Services\Installer\Scripts\CreateAdminAccount::class,
                \Newnet\Core\Services\Installer\Scripts\PublishAssets::class,
                \Newnet\Core\Services\Installer\Scripts\SetInstalledFlag::class,
            ])
            ->install($this);

        if ($success) {
            NewnetInstalled::dispatch();

            $backendUrl = $this->config['app.url'] . '/' . $this->config['core.admin_prefix'];
            $this->info('App ready! You can now login with your username and password at');
            $this->warn(sprintf(" %s", $backendUrl));
        }
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force the installation, even if already installed'],
        ];
    }
}
