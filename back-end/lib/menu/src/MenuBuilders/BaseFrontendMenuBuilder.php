<?php

namespace Newnet\Menu\MenuBuilders;

use Newnet\Menu\Contracts\FrontendMenuBuilderInterface;

abstract class BaseFrontendMenuBuilder implements FrontendMenuBuilderInterface
{
    /**
     * Menu Item Builder Arguments
     *
     * @var array|string
     */
    protected $args = [];

    public function setArgs($args)
    {
        $this->args = $args;

        return $this;
    }
}
