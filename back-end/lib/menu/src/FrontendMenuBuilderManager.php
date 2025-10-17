<?php

namespace Newnet\Menu;

use Newnet\Menu\Contracts\FrontendMenuBuilderInterface;

class FrontendMenuBuilderManager
{
    /**
     * List Frontend Menu Builder Class Name
     *
     * @var array
     */
    protected $builderClass = [];

    /**
     * List Builder Panels
     *
     * @var FrontendMenuBuilderInterface[]
     */
    protected $panels = [];

    protected $loaded = false;

    public function add($className)
    {
        $this->builderClass[] = $className;

        return $this;
    }

    public function getPanels()
    {
        $this->load();

        return $this->panels;
    }

    public function getClassOptions()
    {
        $this->load();

        $classOptions = [];
        foreach ($this->panels as $builder) {
            $classOptions[] = [
                'value' => get_class($builder),
                'label' => $builder->getTitle(),
            ];
        }

        return $classOptions;
    }

    protected function load()
    {
        if ($this->loaded) {
            return null;
        }

        foreach ($this->builderClass as $className) {
            if (class_exists($className)) {
                /** @var FrontendMenuBuilderInterface $builder */
                $builder = app($className);

                $this->panels[] = $builder;
            }
        }

        $this->loaded = true;
    }
}
