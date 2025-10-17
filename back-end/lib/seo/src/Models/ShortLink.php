<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Seo\Models\ShortLink
 *
 * @property int $id
 * @property string $code
 * @property string $content_urls
 * @property string $text
 * @property string|null $css
 * @property string $target
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereContentUrls($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereCss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShortLink whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShortLink extends Model
{
  protected $table = 'seo__short_links';

  protected $fillable = [
    'code',
    'content_urls',
    'text',
    'css',
    'target',
    'is_active',
  ];

  protected $casts = [
    'is_active' => 'boolean',
  ];
}
