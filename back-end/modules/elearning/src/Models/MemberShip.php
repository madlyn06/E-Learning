<?php

namespace Modules\Elearning\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Module\Elearning\Http\Controllers\Web\MembershipController;
use Newnet\Seo\Traits\SeoableTrait;

/**
 * Module\Elearning\Models\MemberShip
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $expire_month
 * @property bool $is_popular
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereExpireMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberShip whereUpdatedAt($value)
 * @property string $content
 * @property mixed $url
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MemberShip whereContent($value)
 * @mixin \Eloquent
 */
class MemberShip extends Model
{
  use HasFactory;
  use SeoableTrait;

  protected $table = 'elearning__memberships';

  protected $fillable = [
    'name',
    'price',
    'expire_month',
    'content',
    'is_popular',
    'is_active',
  ];

  protected $casts = [
    'price' => 'float',
    'is_popular' => 'boolean',
    'is_active' => 'boolean',
  ];

  public function getUrl(): string
  {
    return route('elearning.web.membership.detail', $this->id);
  }
}
