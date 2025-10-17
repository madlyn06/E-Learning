<?php

use Newnet\Zone\Models\ZoneDistrict;
use Newnet\Zone\Models\ZoneProvince;
use Newnet\Zone\Models\ZoneTownship;

if (!function_exists('get_zone_provice_options')) {
    /**
     * Get Zone Province Options
     *
     * @return array
     */
    function get_zone_provice_options()
    {
        $options = [];
        $zoneProvinces = ZoneProvince::whereStatus(1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get([
                'id',
                'name',
            ]);
        foreach ($zoneProvinces as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim($item->name),
            ];
        }
        return $options;
    }
}

if (!function_exists('get_zone_district_options')) {
    /**
     * Get Zone District Options
     *
     * @return array
     */
    function get_zone_district_options()
    {
        $options = [];
        $zoneDistricts = ZoneDistrict::whereStatus(1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get([
                'id',
                'name',
            ]);
        foreach ($zoneDistricts as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim($item->name),
            ];
        }
        return $options;
    }
}

if (!function_exists('get_zone_township_options')) {
    /**
     * Get Zone Township Options
     *
     * @return array
     */
    function get_zone_township_options()
    {
        $options = [];
        $zoneTownships = ZoneTownship::whereStatus(1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get([
                'id',
                'name',
            ]);
        foreach ($zoneTownships as $item) {
            $options[] = [
                'value' => $item->id,
                'label' => trim($item->name),
            ];
        }
        return $options;
    }
}

if (!function_exists('get_province_by_id')) {
    function get_province_by_id($province_id)
    {
        return ZoneProvince::select('name')->where('id', $province_id)->first();
    }
}

if (!function_exists('get_district_by_id')) {
    function get_district_by_id($district_id)
    {
        return ZoneDistrict::select('name')->where('id', $district_id)->first();
    }
}

if (!function_exists('get_township_by_id')) {
    function get_township_by_id($township_id)
    {
        return ZoneTownship::select('name')->where('id', $township_id)->first();
    }
}
