<?php

namespace Newnet\Cms;

class PageLayoutGroup
{
    protected $items = [];

    public function __construct()
    {
        $configPageLayout = config('cms.cms.page.page_layout.options');
        foreach ($configPageLayout as $key => $label) {
            $this->add($key, $label);
        }
    }

    public function all()
    {
        return $this->items;
    }

    public function add($key, $label, $view = null)
    {
        $view = $view ?: "cms::web.page.{$key}";

        $this->items[$key] = [
            'key'   => $key,
            'label' => $label,
            'view'  => $view,
        ];

        return $this;
    }

    public function toOptions()
    {
        return array_map(function ($item) {
            return [
                'value' => $item['key'],
                'label' => $item['label'],
            ];
        }, $this->items);
    }

    public function getLabel($key)
    {
        return $this->items[$key]['label'] ?? ucfirst($key);
    }

    public function getView($key)
    {
        return $this->items[$key]['view'] ?? $key;
    }
}
