<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Module\Elearning\Models\TrackingLogin
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property string $device
 * @property string $last_logged_in
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereLastLoggedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrackingLogin whereUserId($value)
 * @mixin \Eloquent
 */
class TrackingLogin extends Model
{
    use HasFactory;

    protected $table = 'elearning__tracking_logins';

    protected $fillable = [
        'user_id',
        'ip',
        'device',
        'last_logged_in',
    ];
}
