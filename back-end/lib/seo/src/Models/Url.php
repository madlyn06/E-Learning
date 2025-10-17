<?php

namespace Newnet\Seo\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Core\Support\Traits\CacheableTrait;

/**
 * Newnet\Seo\Models\Url
 *
 * @property int $id
 * @property string $urlable_type
 * @property int $urlable_id
 * @property string $request_path
 * @property string $target_path
 * @property string|null $locale
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $urlable
 * @method static \Illuminate\Database\Eloquent\Builder|Url newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Url query()
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereRequestPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereTargetPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereUrlableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Url whereUrlableType($value)
 * @mixin \Eloquent
 */
class Url extends Model
{
    use CacheableTrait;

    protected $table = 'seo__urls';

    protected $fillable = [
        'request_path',
        'target_path',
        'locale',
    ];

    public function __construct(array $attributes = [])
    {
        $this->locale = get_current_edit_locale();

        parent::__construct($attributes);
    }

    public function urlable()
    {
        return $this->morphTo();
    }

    public function setTargetPathAttribute($value)
    {
        $this->attributes['target_path'] = ltrim(rtrim($value, '/'), '/');
    }

    public function setRequestPathAttribute($value)
    {
        $this->attributes['request_path'] = $this->makeSlug($value);
    }

    protected function makeSlug(string $slug)
    {
        $originalSlug = $slug;
        $validSlug = ltrim(rtrim($slug, '/'), '/');
        $i = 1;

        while ($this->otherRecordExistsWithSlug($slug)) {
            if ($validSlug) {
                $slug = $originalSlug.'-'.$i++;
            } else {
                $slug = uniqid();
            }
        }

        if ($slug === '/') {
            return $slug;
        }

        return ltrim(rtrim($slug, '/'), '/');
    }

    protected function otherRecordExistsWithSlug(string $slug): bool
    {
        $key = $this->getKey();

        if ($this->incrementing) {
            $key = $key ?? '0';
        }

        $query = static::where('request_path', $slug)
            ->where($this->getKeyName(), '!=', $key)
            ->withoutGlobalScopes();

        $query->where('locale', get_current_edit_locale());

        if ($this->usesSoftDeletes()) {
            $query->withTrashed();
        }

        return $query->exists();
    }

    protected function usesSoftDeletes()
    {
        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this))) {
            return true;
        }

        return false;
    }
}
