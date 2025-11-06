<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property array<array-key, mixed>|null $config
 * @property bool $is_active
 * @property string|null $logo
 * @property int $display_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Modules\Elearning\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentMethod extends Model
{
    use HasFactory;
    use HasMediaTrait;

    protected $table = 'elearning__payment_methods';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'display_order',
        'config',
        'logo',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Set display order
    public function setDisplayOrderAttribute($value)
    {
        $this->attributes['display_order'] = !empty($value) ? $value : 1;
    }

    public function getConfigAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setConfigAttribute($value)
    {
        $this->attributes['config'] = json_encode($value);
    }

    public function setLogoAttribute($value)
    {
        $this->mediaAttributes['logo'] = $value;
    }

    public function getLogoAttribute()
    {
        return $this->getFirstMedia('logo');
    }
}
