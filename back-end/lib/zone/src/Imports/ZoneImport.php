<?php

namespace Newnet\Zone\Imports;

use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Newnet\Zone\Models\ZoneDistrict;
use Newnet\Zone\Models\ZoneProvince;
use Newnet\Zone\Models\ZoneTownship;

class ZoneImport implements WithHeadingRow, SkipsOnFailure, ToArray, WithChunkReading
{
    protected $districtMap = [];

    protected $provinceMap = [];

    protected $townshipMap = [];

    private $provinceStatus;

    public function __construct($provinceStatus)
    {
        $this->provinceStatus = $provinceStatus;
        $this->createProvinceMap();
        $this->createDistrictMap();
        $this->createTownshipMap();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            \Log::error('ZoneImport@onFailure', [$failure->toArray()]);
        }
    }

    public function array(array $array)
    {
        $townshipImport = [];
        foreach ($array as $item) {
            if (empty($item['ma']) || empty($item['ten'])) {
                continue;
            }

            if (isset($this->townshipMap[$item['ma']])) {
                continue;
            }

            $districtId = $this->getDistrictId($item);
            $townshipImport[] = [
                'name'        => $item['ten'],
                'code'        => $item['ma'],
                'status'      => true,
                'district_id' => $districtId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        try {
            ZoneTownship::insert($townshipImport);
        } catch (\Exception $e) {
            \Log::error('ImportError', [
                'message' => $e->getMessage(),
                'data'    => $townshipImport,
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function getDistrictId(array $item)
    {
        return $this->districtMap[$item['ma_qh']] ?? $this->createDistrict($item);
    }

    private function createDistrict(array $item)
    {
        $provinceId = $this->getProvinceId($item);

        $district = ZoneDistrict::create([
            'name'        => $item['quan_huyen'],
            'code'        => $item['ma_qh'],
            'status'      => true,
            'province_id' => $provinceId,
        ]);

        $this->districtMap[$item['ma_qh']] = $district->id;

        return $district->id;
    }

    private function getProvinceId(array $item)
    {
        return $this->provinceMap[$item['ma_tp']] ?? $this->createProvince($item);
    }

    private function createProvince(array $item)
    {
        $province = ZoneProvince::create([
            'name'       => $item['tinh_thanh_pho'],
            'code'       => $item['ma_tp'],
            'status'     => $this->provinceStatus,
        ]);

        $this->provinceMap[$item['ma_tp']] = $province->id;

        return $province->id;
    }

    private function createProvinceMap()
    {
        $provinces = ZoneProvince::get(['id', 'code']);
        $this->provinceMap = $provinces
            ->keyBy('code')
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }

    private function createDistrictMap()
    {
        $districts = ZoneDistrict::get(['id', 'code']);
        $this->districtMap = $districts
            ->keyBy('code')
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }

    private function createTownshipMap()
    {
        $towships = ZoneTownship::get(['id', 'code']);
        $this->townshipMap = $towships
            ->keyBy('code')
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }
}
