<?php

namespace Newnet\Acl;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Newnet\Acl\Contracts\PermissionManagerInterface;

class PermissionManager implements PermissionManagerInterface
{
    /**
     * All Permission
     * @var array
     */
    protected $permissions = [];

    /**
     * All Permission in tree list
     * @var array
     */
    protected $treePermissions = [];

    protected array $items = [];

    protected bool $loaded = false;

    /**
     * Get all Permission.
     * @return array
     */
    public function all()
    {
        return $this->permissions;
    }

    public function allTree()
    {
        ksort($this->treePermissions);

        return $this->treePermissions;
    }

    /**
     * Get all Permission Without Key.
     * @return array
     */
    public function allTreeWithoutKey()
    {
        $this->autoLoad();

        return $this->removeChildrenKey($this->allTree());
    }

    public function add($key, $label = null)
    {
        $originKey = $key;

        $key = Str::replaceFirst('.admin.', '.', $key);
        $this->permissions[$originKey] = $label;

        $this->setDefaultGroupLabel($key);

        $arrKey = str_replace('.', '.children.', $key);
        Arr::set($this->treePermissions, $arrKey, [
            'key'   => $originKey,
            'label' => $label,
        ]);

        return $this;
    }

    public function setGroupLabel($key, $label)
    {
        $groupLabelKey = str_replace('.', '.children.', $key) . '.label';

        Arr::set($this->treePermissions, $groupLabelKey, $label);

        return $this;
    }

    protected function removeChildrenKey($array)
    {
        $newData = [];

        foreach ($array as $key => $item) {
            if (!empty($item['children'])) {
                $item['children'] = $this->removeChildrenKey($item['children']);
            }

            $newData[] = $item;
        }

        return $newData;
    }

    protected function setDefaultGroupLabel($key)
    {
        $segments = explode('.', $key);

        foreach ($segments as $segment) {
            array_pop($segments);

            if (empty($segments)) {
                continue;
            }

            $groupKey = implode('.', $segments);
            $groupLabelKey = str_replace('.', '.children.', $groupKey) . '.label';

            if (!Arr::has($this->treePermissions, $groupLabelKey)) {
                if (count($segments) == 1) {
                    $label = trans("{$segments[0]}::module.module_name");
                } elseif (count($segments) == 2) {
                    $label = trans("${segments[0]}::{$segments[1]}.model_name");
                } else {
                    $label = ucfirst(last($segments));
                }

                Arr::set($this->treePermissions, $groupLabelKey, $label);
            }
        }
    }

    protected function autoLoad()
    {
        if (!$this->loaded) {
            $routesByName = Route::getRoutes()->getRoutesByName();
            $routesCollection = collect($routesByName);
            $routes = $routesCollection->filter(function ($item) {
                return in_array('admin.acl', $item->middleware());
            })->keys();

            foreach ($routes as $route) {
                $routeExplode = explode('.', $route);

                if (count($routeExplode) == 4) {
                    $module = $routeExplode[0];
                    $model = $routeExplode[2];
                    $action = $routeExplode[3];

                    $tranKey = "{$module}::{$model}.model_name";
                } elseif (count($routeExplode) == 3) {
                    $module = $routeExplode[0];
                    $model = 'module';
                    $action = $routeExplode[2];

                    $tranKey = "{$module}::module.module_name";
                } else {
                    continue;
                }

                if (in_array($action, ['store', 'show', 'update'])) {
                    continue;
                }

                if (trans()->has($tranKey)) {
                    $modelName = trans($tranKey);
                } else {
                    $modelName = Str::ucfirst($model);
                }

                $labelKey = "acl::permission.actions.{$action}";
                $moduleLabelKey = "{$module}::permission.{$model}.{$action}";
                $label = trans($moduleLabelKey);
                if (trans()->has($moduleLabelKey)) {
                    $label = trans($moduleLabelKey);
                } else if (trans()->has($labelKey)) {
                    $label = trans($labelKey, ['name' => $modelName]);
                }

                $this->add($route, $label);
            }

            $this->loaded = true;
        }
    }
}
