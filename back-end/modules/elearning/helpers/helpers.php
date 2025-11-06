<?php

use Modules\Elearning\Models\Category;

if (!function_exists('elearning_get_category_parent_options')) {
    /**
     * Get Category Parent Options
     *
     * @return array
     */
    function elearning_get_category_parent_options()
    {
        $options = [];

        $categoryTreeList = Category::defaultOrder()->withDepth()->get()->toFlatTree();
        foreach ($categoryTreeList as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim(str_pad('', $item->depth * 3, '-')).' '.$item->name,
            ];
        }

        return $options;
    }
}
