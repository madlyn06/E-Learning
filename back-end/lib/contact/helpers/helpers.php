<?php

if (!function_exists('get_contact_label_type_options')) {
    function get_contact_label_type_options()
    {
        return [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'success' => 'Success',
            'danger' => 'Danger',
            'warning' => 'Warning',
            'info' => 'Info',
            'light' => 'Light',
            'dark' => 'Dark',
            'link' => 'Link',
        ];
    }
}

if (!function_exists('get_contact_label_options')) {
    function get_contact_label_options()
    {
        $options = [];
        $labels = \Newnet\Contact\Models\Label::all();
        foreach ($labels as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => $item->name,
            ];
        }
        return $options;
    }
}
