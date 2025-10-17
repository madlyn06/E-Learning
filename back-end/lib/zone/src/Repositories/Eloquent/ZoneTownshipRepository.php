<?php
namespace Newnet\Zone\Repositories\Eloquent;

use Newnet\Zone\Models\ZoneTownship;
use Newnet\Core\Repositories\BaseRepository;

class ZoneTownshipRepository extends BaseRepository implements ZoneTownshipRepositoryInterface
{
    public function __construct(ZoneTownship $zoneTownship)
    {
        parent::__construct($zoneTownship);
    }

    public function allActiveWithSortOf($districtId, $columns = ['*'])
    {
        return $this->model
            ->where('status', 1)
            ->where('district_id', $districtId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get($columns);
    }
}
