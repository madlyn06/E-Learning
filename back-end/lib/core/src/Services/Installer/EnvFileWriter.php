<?php

namespace Newnet\Core\Services\Installer;

use Illuminate\Filesystem\Filesystem;

class EnvFileWriter
{
    /**
     * @var Filesystem
     */
    private $finder;

    /**
     * Whitelist of variables in .env.example that can be written by the installer when it creates the .env file
     *
     * @var array
     */
    protected $setable_variables = [
        'app_name'     => 'APP_NAME=Laravel',
        'db_driver'    => 'DB_CONNECTION=mysql',
        'db_host'      => 'DB_HOST=127.0.0.1',
        'db_port'      => 'DB_PORT=3306',
        'db_database'  => 'DB_DATABASE=laravel',
        'db_username'  => 'DB_USERNAME=root',
        'db_password'  => 'DB_PASSWORD=',
        'app_url'      => 'APP_URL=http://localhost',
        'installed'    => 'INSTALLED=false',
        'admin_prefix' => 'ADMIN_PREFIX=admin',
    ];

    /**
     * @var string
     */
    protected $template = '.env.example';

    /**
     * @var string
     */
    protected $file = '.env';

    /**
     * @param Filesystem $finder
     */
    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
        $this->file = app()->environmentPath().DIRECTORY_SEPARATOR.'.env';
    }

    /**
     * Create a new .env file using the contents of .env.example
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function create()
    {
        $environmentFile = $this->finder->get($this->template);

        $this->finder->put($this->file, $environmentFile);
    }

    /**
     * Update the .env file
     *
     * @param array $vars
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function write($vars)
    {
        if (!empty($vars)) {
            $environmentFile = $this->finder->get($this->file);

            foreach ($vars as $key => $value) {
                if (isset($this->setable_variables[$key])) {
                    $env_var_name = explode('=', $this->setable_variables[$key])[0];

                    $value = $env_var_name . '=' . $value;

                    $environmentFile = str_replace($this->setable_variables[$key], $value, $environmentFile);
                }
            }

            $this->finder->put($this->file, $environmentFile);
        }
    }
}
