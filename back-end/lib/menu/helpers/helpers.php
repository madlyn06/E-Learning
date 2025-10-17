<?php

use Newnet\Menu\Models\MenuItem;

if (!function_exists('module_menu__get_menu_item_parent_options')) {
    /**
     * @param $menuId
     * @return array
     */
    function module_menu__get_menu_item_parent_options($menuId)
    {
        $options = [];

        $categoryTreeList = MenuItem::whereMenuId($menuId)->withDepth()->get()->toFlatTree();
        foreach ($categoryTreeList as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim(str_pad('', $item->depth * 3, '-')).' '.$item->label,
            ];
        }

        return $options;
    }
}
