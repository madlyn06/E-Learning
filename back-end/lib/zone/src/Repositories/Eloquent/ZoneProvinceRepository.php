<?php
namespace Newnet\Zone\Repositories\Eloquent;

use Newnet\Zone\Models\ZoneProvince;
use Newnet\Core\Repositories\BaseRepository;

class ZoneProvinceRepository extends BaseRepository implements ZoneProvinceRepositoryInterface
{
    public function __construct(ZoneProvince $zoneProvince)
    {
        parent::__construct($zoneProvince);
    }

    public function allActiveWithSort($columns = ['*'])
    {
        return $this->model
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get($columns);
    }
}
