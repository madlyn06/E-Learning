<?php

namespace Newnet\Dashboard;

use Illuminate\Support\Collection;

class DashboardManager
{
    /**
     * Dashboard Class Name List
     *
     * @var array
     */
    protected $items = [];

    /**
     * Dashboard Item Collection
     *
     * @var Collection
     */
    protected $collection;

    public function push($dashboardClassName)
    {
        $this->items[] = $dashboardClassName;

        return $this;
    }

    public function all()
    {
        $this->load();

        return $this->collection;
    }

    public function onlyCanAccess()
    {
        $this->load();

        return $this->collection->filter(function (DashboardInterface $item) {
            return !$item->permission() || admin_can($item->permission());
        });
    }

    public function only($keys)
    {
        $this->load();

        return $this->collection
            ->only($keys)
            ->sortBy(function ($model) use ($keys) {
                return array_search($model->id(), $keys);
            });
    }

    protected function load()
    {
        if (!$this->collection) {
            $this->collection = new Collection();

            foreach (array_unique($this->items) as $item) {
                if (class_exists($item)) {
                    /** @var DashboardInterface $dashboard */
                    $dashboard = new $item;
                    $this->collection->put($dashboard->id(), new $dashboard);
                }
            }
        }

        return $this;
    }
}
