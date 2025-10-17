<?php

namespace Newnet\Acl\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Newnet\Acl\Traits\HasPermission;
use Newnet\Core\Support\Traits\CacheableTrait;
use Newnet\Media\Traits\HasMediaTrait;
use Newnet\Seo\Traits\SeoableTrait;

/**
 * Newnet\Acl\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property bool $is_admin
 * @property string|null $permissions
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $settings
 * @property-read mixed $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Acl\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @property bool $is_active
 * @property string|null $description
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $youtube
 * @property string|null $pinterest
 * @property string|null $linkedin
 * @property string|null $twitter
 * @property string|null $content
 * @property-read mixed $image
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePinterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereTwitter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereYoutube($value)
 * @mixin \Eloquent
 */
class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasPermission;
    use HasMediaTrait;
    use CacheableTrait;
    use HasApiTokens;
    use SeoableTrait;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'title',
        'password',
        'is_admin',
        'permissions',
        'settings',
        'is_active',
        'avatar',
        'description',
        'address',
        'content',
        'facebook',
        'instagram',
        'youtube',
        'pinterest',
        'linkedin',
        'twitter',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function setAvatarAttribute($value)
    {
        $this->mediaAttributes['avatar'] = $value;
    }

    public function getImageAttribute()
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMediaThumb('avatar');
        }

        return config('cms.acl.default_avatar') ?: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mOccAMAAf4BaqRcrJYAAAAASUVORK5CYII=';
    }

    public function getAvatarAttribute()
    {
        if ($this->hasMedia('avatar')) {
            return $this->getFirstMedia('avatar');
        }

        return config('cms.acl.default_avatar') ?: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mOccAMAAf4BaqRcrJYAAAAASUVORK5CYII=';
    }

    public function getUrl()
    {
        return route('acl.web.admin.detail', $this->id);
    }
}
