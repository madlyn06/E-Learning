<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Newnet\Cms\Models\Keywordable
 *
 * @property int $keyword_id
 * @property string $keywordable_type
 * @property int $keywordable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Newnet\Cms\Models\Keyword $keyword
 * @property-read Model|\Eloquent $keywordable
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable whereKeywordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable whereKeywordableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable whereKeywordableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Keywordable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Keywordable extends Model
{
    protected $table = 'cms__keywordables';

    protected $fillable = ['keyword_id', 'keywordable_type', 'keywordable_id'];

    public function keyword()
    {
        return $this->belongsTo(Keyword::class, 'keyword_id');
    }

    public function keywordable(): MorphTo
    {
        return $this->morphTo();
    }
}
