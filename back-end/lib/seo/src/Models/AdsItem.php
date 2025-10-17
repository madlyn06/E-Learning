<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Media\Traits\HasMediaTrait;
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

class AdsItem extends Model
{
    use HasMediaTrait;

    protected $table = 'seo__ads_items';

    protected $fillable = [
        'ads_id',
        'title',
        'image',
        'image_search_google',
        'is_active',
    ];

    public function ads()
    {
        return $this->belongsTo(Ads::class);
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function setImageSearchGoogleAttribute($value)
    {
        $this->mediaAttributes['image_search_google'] = $value;
    }

    public function getImageSearchGoogleAttribute()
    {
        return $this->getFirstMedia('image_search_google');
    }
}
