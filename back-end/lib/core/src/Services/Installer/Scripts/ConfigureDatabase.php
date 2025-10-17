<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\DatabaseManager;
use Newnet\Core\Services\Installer\EnvFileWriter;
use Newnet\Core\Services\Installer\SetupScript;
use PDOException;

class ConfigureDatabase implements SetupScript
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var EnvFileWriter
     */
    protected $env;

    /**
     * @param Config $config
     * @param EnvFileWriter $env
     */
    public function __construct(Config $config, EnvFileWriter $env)
    {
        $this->config = $config;
        $this->env = $env;
    }

    /**
     * @var Command
     */
    protected $command;

    /**
     * Fire the install script
     * @param Command $command
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $connected = false;

        $vars = [];

        while (!$connected) {
            $vars['db_driver'] = $this->askDatabaseDriver();
            $vars['db_host'] = $this->askDatabaseHost();
            $vars['db_port'] = $this->askDatabasePort($vars['db_driver']);
            $vars['db_database'] = $this->askDatabaseName();
            $vars['db_username'] = $this->askDatabaseUsername();
            $vars['db_password'] = $this->askDatabasePassword();

            $this->setLaravelConfiguration($vars);

            if ($this->databaseConnectionIsValid()) {
                $connected = true;
            } else {
                $command->error("Please ensure your database credentials are valid.");
            }
        }

        $this->env->write($vars);

        $command->info('Database successfully configured');
    }

    /**
     * @return string
     */
    protected function askDatabaseDriver()
    {
        $driver = $this->command->ask('Enter your database driver (e.g. mysql, pgsql)', 'mysql');

        return $driver;
    }

    /**
     * @return string
     */
    protected function askDatabaseHost()
    {
        $host = $this->command->ask('Enter your database host', '127.0.0.1');

        return $host;
    }

    /**
     * @param $driver
     * @return string
     */
    protected function askDatabasePort($driver)
    {
        $port = $this->command->ask('Enter your database port', $this->config['database.connections.' . $driver . '.port']);

        return $port;
    }

    /**
     * @return string
     */
    protected function askDatabaseName()
    {
        do {
            $name = $this->command->ask('Enter your database name', 'laravel');
            if ($name == '') {
                $this->command->error('Database name is required');
            }
        } while (!$name);

        return $name;
    }

    /**
     * @param
     * @return string
     */
    protected function askDatabaseUsername()
    {
        do {
            $user = $this->command->ask('Enter your database username', 'root');
            if ($user == '') {
                $this->command->error('Database username is required');
            }
        } while (!$user);

        return $user;
    }

    /**
     * @param
     * @return string
     */
    protected function askDatabasePassword()
    {
        $databasePassword = $this->command->ask('Enter your database password (leave <none> for no password)', '');

        return ($databasePassword === '<none>') ? '' : $databasePassword;
    }

    /**
     * @param array $vars
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function setLaravelConfiguration($vars)
    {
        $driver = $vars['db_driver'];

        $this->config['database.default'] = $driver;
        $this->config['database.connections.' . $driver . '.host'] = $vars['db_host'];
        $this->config['database.connections.' . $driver . '.port'] = $vars['db_port'];
        $this->config['database.connections.' . $driver . '.database'] = $vars['db_database'];
        $this->config['database.connections.' . $driver . '.username'] = $vars['db_username'];
        $this->config['database.connections.' . $driver . '.password'] = $vars['db_password'];

        app(DatabaseManager::class)->purge($driver);
        app(ConnectionFactory::class)->make($this->config['database.connections.' . $driver], $driver);
    }

    /**
     * Is the database connection valid?
     * @return bool
     */
    protected function databaseConnectionIsValid()
    {
        try {
            app('db')->reconnect()->getPdo();

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
