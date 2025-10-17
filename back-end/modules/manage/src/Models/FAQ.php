<?php

namespace Modules\Manage\Models;

use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;

/**
 * Modules\Manage\Models\FAQ
 *
 * @property int $id
 * @property array $name
 * @property array $answer
 * @property string|null $icon
 * @property array|null $content
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property mixed $url
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ query()
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereUpdatedAt($value)
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|FAQ whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @mixin \Eloquent
 */
class FAQ extends Model
{
    use TranslatableTrait;
    use SeoableTrait;

    protected $table = 'manage__faqs';

    protected $fillable = [
        'name',
        'answer',
        'icon',
        'content',
        'is_active',
    ];

    public $translatable = [
        'name',
        'answer',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getUrl()
    {
        return route('manage.web.faq.detail', $this->id);
    }
}
