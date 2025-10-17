<?php

namespace Newnet\Dashboard\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Newnet\Core\Repositories\BaseRepositoryInterface;

interface DashboardRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get My Dashboard
     *
     * @return Collection
     */
    public function myDashboard();
}
