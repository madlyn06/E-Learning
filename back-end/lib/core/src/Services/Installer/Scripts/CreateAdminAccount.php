<?php

namespace Newnet\Core\Services\Installer\Scripts;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Newnet\Acl\Models\Admin;
use Newnet\Acl\Repositories\AdminRepositoryInterface;
use Newnet\Core\Services\Installer\SetupScript;

class CreateAdminAccount implements SetupScript
{
    /**
     * @var AdminRepositoryInterface
     */
    private $adminRepository;

    /**
     * @param AdminRepositoryInterface $adminRepository
     */
    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * @var Command
     */
    protected $command;

    /**
     * Fire the install script
     * @param Command $command
     */
    public function fire(Command $command)
    {
        $this->command = $command;

        $this->createFirstAdminUser();
    }

    /**
     * Create a first admin user
     */
    protected function createFirstAdminUser()
    {
        $info = [
            'is_admin' => true,
            'name'     => $this->askForName(),
            'email'    => $this->askForEmail(),
            'password' => $this->askForPassword(),
        ];
        $info['slug'] = Str::slug($info['name']);

        $this->command->info('Please wait while the admin account is configured...');

        /** @var Admin $admin */
        $admin = $this->adminRepository->create($info);
        $admin->markEmailAsVerified();
    }

    /**
     * @return string
     */
    private function askForName()
    {
        if ($name = $this->getValueFromOption('name')) {
            return $name;
        }

        do {
            $name = $this->command->ask('Enter your name');
            if ($name == '') {
                $this->command->error('Name is required');
            }
        } while (!$name);

        return $name;
    }

    /**
     * @return string
     */
    private function askForEmail()
    {
        if ($email = $this->getValueFromOption('email')) {
            return $email;
        }

        do {
            $email = $this->command->ask('Enter your email address');
            if ($email == '') {
                $this->command->error('Email is required');
            }
        } while (!$email);

        return $email;
    }

    /**
     * @return string
     */
    private function askForPassword()
    {
        if ($password = $this->getValueFromOption('password')) {
            return $password;
        }

        do {
            $password = $this->askForFirstPassword();
            $passwordConfirmation = $this->askForPasswordConfirmation();
            if ($password != $passwordConfirmation) {
                $this->command->error('Password confirmation doesn\'t match. Please try again.');
            }
        } while ($password != $passwordConfirmation);

        return $password;
    }

    /**
     * @return string
     */
    private function askForFirstPassword()
    {
        do {
            $password = $this->command->secret('Enter a password');
            if ($password == '') {
                $this->command->error('Password is required');
            }
        } while (!$password);

        return $password;
    }

    /**
     * @return string
     */
    private function askForPasswordConfirmation()
    {
        do {
            $passwordConfirmation = $this->command->secret('Please confirm your password');
            if ($passwordConfirmation == '') {
                $this->command->error('Password confirmation is required');
            }
        } while (!$passwordConfirmation);

        return $passwordConfirmation;
    }

    protected function getValueFromOption($option)
    {
        if ($this->command->hasOption($option)) {
            return $this->command->option($option);
        }
    }
}
