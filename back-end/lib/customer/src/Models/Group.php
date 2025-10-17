<?php

namespace Newnet\Customer\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Customer\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $slug
 * @property-read \Illuminate\Database\Eloquent\Collection|\Newnet\Customer\Models\Customer[] $customers
 * @property-read int|null $customers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Newnet\Customer\Models\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    protected $table = 'customer__groups';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
