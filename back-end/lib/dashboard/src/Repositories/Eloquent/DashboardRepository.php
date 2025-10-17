<?php

namespace Newnet\Dashboard\Repositories\Eloquent;

use Newnet\Acl\Models\Admin;
use Newnet\Core\Repositories\BaseRepository;
use Newnet\Dashboard\Models\Dashboard;
use Newnet\Dashboard\Repositories\DashboardRepositoryInterface;

class DashboardRepository extends BaseRepository implements DashboardRepositoryInterface
{
    public function myDashboard()
    {
        return $this->model
            ->whereHasMorph('author', Admin::class, function ($q) {
                $q->where('id', $this->currentAdmin()->id);
            })
            ->orderBy('sort_order', 'ASC')
            ->get();
    }

    public function syncMyDashboard($classNames)
    {
        $this->emptyMyDashboard();

        foreach ($classNames as $key => $className) {
            $item = new $this->model;
            $item->fill([
                'class_name' => $className,
                'sort_order' => $key + 1,
            ]);
            $item->author()->associate($this->currentAdmin());
            $item->save();
        }
    }

    public function emptyMyDashboard()
    {
        $items = $this->model
            ->whereHasMorph('author', Admin::class, function ($q) {
                $q->where('id', $this->currentAdmin()->id);
            })
            ->get();

        foreach ($items as $item) {
            $item->delete();
        }
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|Admin|null
     */
    protected function currentAdmin()
    {
        return \Auth::guard('admin')->user();
    }
}
