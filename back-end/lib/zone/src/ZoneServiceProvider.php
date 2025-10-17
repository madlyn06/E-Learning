<?php

namespace Newnet\Zone;

use Illuminate\Support\Facades\Event;
use Newnet\Zone\Console\Commands\DownloadCommand;
use Newnet\Zone\Console\Commands\UpdateCommand;
use Newnet\Zone\Repositories\Eloquent\ZoneDistrictRepository;
use Newnet\Zone\Repositories\Eloquent\ZoneDistrictRepositoryInterface;
use Newnet\Zone\Repositories\Eloquent\ZoneProvinceRepository;
use Newnet\Zone\Repositories\Eloquent\ZoneProvinceRepositoryInterface;
use Newnet\Zone\Repositories\Eloquent\ZoneTownshipRepository;
use Newnet\Zone\Repositories\Eloquent\ZoneTownshipRepositoryInterface;
use Newnet\Acl\Facades\Permission;
use Newnet\Module\Support\BaseModuleServiceProvider;

class ZoneServiceProvider extends BaseModuleServiceProvider
{
    public function registerPermissions()
    {
        Permission::add('zone.admin.province.index', __('zone::permission.province.index'));
        Permission::add('zone.admin.province.create', __('zone::permission.province.create'));
        Permission::add('zone.admin.province.edit', __('zone::permission.province.edit'));
        Permission::add('zone.admin.province.destroy', __('zone::permission.province.destroy'));

//        Permission::add('zone.admin.import.index', __('zone::permission.import.index'));
    }

    public function register()
    {
        parent::register();

        $this->app->bind(ZoneProvinceRepositoryInterface::class, ZoneProvinceRepository::class);
        $this->app->bind(ZoneDistrictRepositoryInterface::class, ZoneDistrictRepository::class);
        $this->app->bind(ZoneTownshipRepositoryInterface::class, ZoneTownshipRepository::class);

        $this->commands([
            DownloadCommand::class,
            UpdateCommand::class,
        ]);
        require_once __DIR__.'/../helpers/helpers.php';
    }
}
