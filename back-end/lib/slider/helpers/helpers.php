<?php

if (!function_exists('get_slider_layout_options')) {
    function get_slider_layout_options()
    {
        $options = [];

        $layouts = config('cms.slider.layouts');
        foreach ($layouts as $layout) {
            $options[] = [
                'value' => $layout,
                'label' => get_slider_layout_label($layout),
            ];
        }

        return $options;
    }
}

if (!function_exists('get_slider_layout_label')) {
    function get_slider_layout_label($layout)
    {
        $langKey = "slider::slider.layouts.{$layout}";

        return Lang::has($langKey) ? Lang::get($langKey) : Str::ucfirst($layout);
    }
}
