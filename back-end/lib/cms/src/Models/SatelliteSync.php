<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatelliteSync extends Model
{
    use HasFactory;

    const SYNC_ALL_SITE = 'Sync all sites';

    protected $table = 'cms__satellites_sync';

    protected $fillable = [
        'satellite_site',
        'message',
        'status',
    ];

    public function getSitesNameAttribute()
    {
        if ($this->satellite_site == self::SYNC_ALL_SITE) {
            return self::SYNC_ALL_SITE;
        }
        $sites = json_decode($this->satellite_site, true);
        $satellites = Satellite::select('name')->whereIn('id', $sites)->get();
        $name = '';
        foreach ($satellites as $key => $satellite) {
            $seperate = $key == count($satellites) - 1 ? '' : ', ';
            $name .= $satellite->name . $seperate;
        }
        return $name;
    }
}
