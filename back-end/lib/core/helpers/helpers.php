<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

if (!function_exists('menu_level_text')) {
    function menu_level_text($level)
    {
        $levelTexts = [
            1 => 'first',
            2 => 'second',
            3 => 'third',
            4 => 'fourth',
        ];

        return Arr::get($levelTexts, $level, $level);
    }
}

if (!function_exists('get_selected_value')) {
    function get_selected_value(?string $value, $selected)
    {
        $selection = $selected instanceof Arrayable ? $selected->toArray() : $selected;

        if (is_array($selection)) {
            if (in_array($value, $selection, true) || in_array((string) $value, $selection, true)) {
                return 'selected';
            } elseif ($selected instanceof Collection) {
                return $selected->contains($value) ? 'selected' : null;
            }

            return null;
        }

        if (is_int($value) && is_bool($selected)) {
            return (bool) $value === $selected;
        }

        return ((string) $value === (string) $selected) ? 'selected' : null;
    }
}

if (!function_exists('get_setting_media')) {
    function get_setting_media($name)
    {
        $media = setting($name);
        if ($media) {
            if (!$media instanceof \Newnet\Media\Models\Media) {
                $media = \Newnet\Media\Models\Media::find($media);
            }

            return $media;
        }

        return null;
    }
}

if (!function_exists('get_setting_media_url')) {
    /**
     * Get setting media url
     *
     * @param $name
     * @param  string  $conversion
     * @param  null  $default
     * @return mixed|null
     */
    function get_setting_media_url($name, $conversion = '', $default = null)
    {
        $media = get_setting_media($name);

        if ($media) {
            if ($conversion) {
                $conversionUpper = 'to'.ucfirst($conversion).'Url';
                return $media->$conversionUpper();
            }
            return (string) $media;
        }

        return $default;
    }
}

if (!function_exists('get_dot_array_form')) {
    /**
     * Transform form name to array dot getter
     * Ex: for[bar] to for.bar
     *
     * @param $name
     * @return string
     */
    function get_dot_array_form($name)
    {
        return preg_replace_callback('/\[([\w]+)\]/', function ($item) {
            return '.'.$item[1];
        }, $name);
    }
}

if (!function_exists('get_active_class')) {
    /**
     * Return active class by condition
     *
     * @param $condition
     * @param  string  $className
     * @return string
     */
    function get_active_class($condition, $className = 'active')
    {
        return $condition ? $className : '';
    }
}

if (!function_exists('add_query_to_url')) {
    /**
     * Add Query String Variable To Exists Url
     *
     * @param $url
     * @param $key
     * @param $value
     * @return string
     */
    function add_query_to_url($url, $key, $value)
    {
        $url = preg_replace('/(.*)(?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
        $url = substr($url, 0, -1);

        if (strpos($url, '?') === false) {
            return ($url.'?'.$key.'='.$value);
        } else {
            return ($url.'&'.$key.'='.$value);
        }
    }
}

if (!function_exists('remove_query_from_url')) {
    /**
     * Remove Query String Variable From Exists Url
     *
     * @param $url
     * @param $key
     * @return string
     */
    function remove_query_from_url($url, $key)
    {
        $url = preg_replace('/(.*)(?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
        $url = substr($url, 0, -1);

        return ($url);
    }
}

if (!function_exists('get_current_edit_locale')) {
    /**
     * Get current edit locale
     *
     * @return string
     */
    function get_current_edit_locale()
    {
        return request('edit_locale') ?: config('app.locale');
    }
}

if (!function_exists('get_current_edit_locale_name')) {
    /**
     * Get current edit locale name
     *
     * @return string
     */
    function get_current_edit_locale_name()
    {
        $locale = get_current_edit_locale();

        $supportedLocales = config('laravellocalization.supportedLocales') ?: [];

        if (isset($supportedLocales[$locale])) {
            return $supportedLocales[$locale]['native'].' ('.$supportedLocales[$locale]['name'].')';
        }

        return Str::upper($locale);
    }
}

if (!function_exists('get_flag_img')) {
    /**
     * Get flag icon image
     *
     * @param $locale
     * @return string
     */
    function get_flag_img($locale)
    {
        $supportedLocales = config('cms.adminui.langs') ?: [];

        $longCode = $supportedLocales[$locale]['regional'] ?? Str::upper($locale);
        $flagCode = strtolower(substr($longCode, 3));

        return asset('vendor/newnet-admin/img/flags/'.$flagCode.'.png');
    }
}

if (!function_exists('get_mail_driver_options')) {
    /**
     * Get Mail Driver Options
     *
     * @return array
     */
    function get_mail_driver_options()
    {
        return [
            [
                'value' => 'smtp',
                'label' => 'Smtp'
            ],
            [
                'value' => 'ses',
                'label' => 'Amazon SES'
            ],
        ];
    }
}

if (!function_exists('vnmoney')) {
    /**
     * Viet Nam Money Format
     *
     * @param $value
     * @return string
     */
    function vnmoney($value)
    {
        return number_format($value, 0, ',', '.');
    }
}

if (!function_exists('is_admin_screen')) {
    /**
     * Check if is admin screen
     *
     * @return boolean
     */
    function is_admin_screen()
    {
        return request()->is(config('core.admin_prefix').'*');
    }
}
