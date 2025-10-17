<?php

use Illuminate\Support\Facades\Auth;
use Newnet\Acl\Repositories\RoleRepositoryInterface;

if (!function_exists('admin_can')) {
    /**
     * Check admin user has permission
     *
     * @param $permission
     * @return bool
     */
    function admin_can($permission)
    {
        /** @var \Newnet\Acl\Models\Admin $user */
        $user = Auth::guard('admin')->user();

        if ($user && $user->hasPermission($permission)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check account is administrator
     *
     * @return bool
     */
    function is_admin()
    {
        /** @var \Newnet\Acl\Models\Admin $user */
        $user = Auth::guard('admin')->user();

        if ($user->is_admin) {
            return true;
        }

        foreach ($user->roles as $role) {
            if ($role->is_admin) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('is_admin_user_logged_in')) {
    /**
     * Determines whether the current admin user is a logged in.
     *
     * @return bool
     */
    function is_admin_user_logged_in()
    {
        return Auth::guard('admin')->check();
    }
}

if (!function_exists('current_admin')) {
    /**
     * Get current admin account
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Newnet\Acl\Models\Admin|null
     */
    function current_admin()
    {
        return Auth::guard('admin')->user();
    }
}

if (!function_exists('get_current_admin')) {
    /**
     * Alias current_admin()
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\Newnet\Acl\Models\Admin|null
     */
    function get_current_admin()
    {
        return current_admin();
    }
}

if (!function_exists('get_current_admin_id')) {
    function get_current_admin_id()
    {
        return Auth::guard('admin')->id();
    }
}

if (!function_exists('get_admin_setting')) {
    function get_admin_setting($key, $default = null)
    {
        $currentAdmin = current_admin();
        $settings = object_get($currentAdmin, 'settings');

        return \Illuminate\Support\Arr::get($settings, $key) ?: $default;
    }
}

if (!function_exists('set_admin_setting')) {
    function set_admin_setting($key, $value)
    {
        $currentAdmin = current_admin();
        if ($currentAdmin) {
            $settings = object_get($currentAdmin, 'settings');
            $settings[$key] = $value;
            $currentAdmin->update([
                'settings' => $settings,
            ]);
        }
    }
}

if (!function_exists('array_merge_recursive_distinct')) {
    function array_merge_recursive_distinct(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = array_merge_recursive_distinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

if (!function_exists('get_acl_role_options')) {
    /**
     * Get Role Options
     *
     * @return array
     */
    function get_acl_role_options()
    {
        $options = [];

        foreach (app(RoleRepositoryInterface::class)->all(['id', 'name', 'display_name']) as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => $item->display_name ?: $item->name,
            ];
        }

        return $options;
    }
}

if (!function_exists('get_admin_echo_token')) {
    /**
     * Get Admin Echo Token
     *
     * @return string
     */
    function get_admin_echo_token()
    {
        return Session::remember('echo_token', function () {
            $admin = get_current_admin();

            $accessToken = $admin->createToken('echo', ['broadcasting']);

            return $accessToken->plainTextToken;
        });
    }
}
