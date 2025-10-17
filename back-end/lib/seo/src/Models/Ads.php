<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;

/**
 * Newnet\Seo\Models\Ads
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $code
 * @property string $content
 * @property int $count
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $image
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ads newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Ads extends Model
{
  use SeoableTrait;

  protected $table = 'seo__ads';

  protected $fillable = [
    'name',
    'title',
    'code',
    'hashed',
    'content',
    'count',
    'is_active',
    'valid_from',
    'valid_to',
    'btn_name',
    'icon_btn',
  ];

  protected $casts = [
    'is_active' => 'boolean',
    'valid_from' => 'datetime',
    'valid_to' => 'datetime',
  ];

  public function getUrl()
  {
    return route('seo.ads.web.detail', $this->id);
  }

  public function adsItems()
  {
    return $this->hasMany(AdsItem::class);
  }

  public function isValid()
  {
    if (!$this->valid_from && !$this->valid_to) {
      return true;
    }
    if ($this->valid_from && !$this->valid_to) {
      if (now() > $this->valid_from) {
        return true;
      }
      return false;
    }
    if (!$this->valid_from && $this->valid_to) {
      if (now() < $this->valid_to) {
        return true;
      }
      return false;
    }

    if ($this->valid_from && $this->valid_to) {
      if (now() > $this->valid_from &&  now() < $this->valid_to) {
        return true;
      }
      return false;
    }
    return false;
  }
}
