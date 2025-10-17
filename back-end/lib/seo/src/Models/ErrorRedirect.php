<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Seo\Models\ErrorRedirect
 *
 * @property int $id
 * @property string $from_path
 * @property string $to_url
 * @property int|null $status_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect query()
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereFromPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereStatusCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereToUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ErrorRedirect whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ErrorRedirect extends Model
{
    protected $table = 'seo__error_redirects';

    protected $fillable = [
        'from_path',
        'to_url',
        'status_code',
    ];

    protected $casts = [
        'status_code' => 'int',
    ];

    public function getStatusCodeAttribute($value)
    {
        return $value ?: 302;
    }

    public function setFromPathAttribute($value)
    {
        $path = parse_url($value, PHP_URL_PATH);
        $query = parse_url($value, PHP_URL_QUERY);
        $value = $query ? $path . '?' . $query : $path;
        $this->attributes['from_path'] = rtrim($value, '/');
    }
}
