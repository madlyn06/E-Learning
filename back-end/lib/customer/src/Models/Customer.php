<?php

namespace Newnet\Customer\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Newnet\Customer\Models\Customer
 *
 * @property int $id
 * @property int|null $group_id
 * @property string|null $prefix e.g.: Mr, Mrs, Miss,...
 * @property string|null $name Full Name
 * @property string|null $email
 * @property string|null $phone
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $remember_token
 * @property string|null $country_code_residence
 * @property string|null $country_code
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Newnet\Customer\Models\Group|null $group
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Customer extends Authenticatable
{
    use Notifiable;
    use HasMediaTrait;

    protected $table = 'customer__customers';

    protected $fillable = [
        'group_id',
        'prefix',
        'name',
        'email',
        'phone',
        'password',
        'is_active',
        'birthday',
        'gender',
        'avatar',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'birthday',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function setAvatarAttribute($value)
    {
        $this->mediaAttributes['avatar'] = $value;
    }

    public function getAvatarAttribute()
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMedia('avatar');
        }

        return config('cms.customer.default_avatar') ?: asset('vendor/newnet-admin/dist/img/avatar-1.jpg');
    }
}
