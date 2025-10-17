<?php

namespace Newnet\Dashboard;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;

abstract class DashboardItem implements DashboardInterface, Htmlable, Arrayable
{
    public function id()
    {
        return get_called_class();
    }

    public function col()
    {
        return 12;
    }

    public function permission()
    {
        return null;
    }

    public function toArray()
    {
        return [
            'id'   => get_called_class(),
            'name' => $this->name(),
        ];
    }
}
