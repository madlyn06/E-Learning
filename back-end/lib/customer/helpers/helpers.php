<?php

if (!function_exists('get_customer_group_options')) {
    function get_customer_group_options()
    {
        $options = [];
        $groups = \Newnet\Customer\Models\Group::all();
        foreach ($groups as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim($item->name),
            ];
        }

        return $options;
    }
}

if (!function_exists('get_customer_options')) {
    function get_customer_options()
    {
        $options = [];
        $customers = \Newnet\Customer\Models\Customer::all();
        foreach ($customers as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim($item->name),
            ];
        }

        return $options;
    }
}

if (!function_exists('current_customer')) {
    /**
     * Get Current Customer
     *
     * @return Customer|null
     */
    function current_customer()
    {
        return get_current_customer();
    }
}

if (!function_exists('get_current_customer')) {
    /**
     * Get Current Customer
     *
     * @return Customer|null
     */
    function get_current_customer()
    {
        return Auth::guard('customer')->user();
    }
}

if (!function_exists('get_current_customer_id')) {
    /**
     * Get Current Customer ID
     *
     * @return int|null
     */
    function get_current_customer_id()
    {
        return Auth::guard('customer')->id() ?? 0;
    }
}

if (!function_exists('get_current_customer_field')) {
    /**
     * Get current customer field
     *
     * @param $field
     * @return mixed|object
     */
    function get_current_customer_field($field)
    {
        $customer = get_current_customer();

        return object_get($customer, $field);
    }
}
