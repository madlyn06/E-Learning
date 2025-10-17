<?php

namespace Newnet\Zone\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Zone\Models\ZoneProvince
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property bool $status
 * @property int|null $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Zone\Models\ZoneDistrict[] $districts
 * @property-read int|null $districts_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Zone\Models\ZoneProvince whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ZoneProvince extends Model
{
    protected $table = 'zone_provinces';

    protected $fillable = [
        'name',
        'code',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function districts()
    {
        return $this->hasMany(ZoneDistrict::class, 'province_id', 'id');
    }

    public function getNameAttribute($value)
    {
        return trim(preg_replace('/Thành phố|Tỉnh/', '', $value));
    }
}
