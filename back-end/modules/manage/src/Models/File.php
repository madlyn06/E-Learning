<?php

namespace Modules\Manage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Newnet\Seo\Traits\SeoableTrait;
use Newnet\Core\Support\Traits\TranslatableTrait;
use Newnet\Media\Traits\HasMediaTrait;

/**
 * Modules\Manage\Models\File
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $company_name
 * @property string|null $file_version
 * @property string|null $file_size
 * @property string|null $download_count
 * @property string|null $download_url
 * @property string|null $post_url
 * @property string|null $required
 * @property \Illuminate\Support\Carbon|null $published_date
 * @property string|null $description
 * @property string|null $content
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Kalnoy\Nestedset\Collection<int, \Modules\Manage\Models\FileCategory> $file_categories
 * @property-read int|null $file_categories_count
 * @property mixed $file
 * @property mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Media\Models\Media> $media
 * @property-read int|null $media_count
 * @property \Newnet\Seo\Models\Meta|null $seometa
 * @property \Newnet\Seo\Models\Url|null $seourl
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Seo\Models\Url> $seourls
 * @property-read int|null $seourls_count
 * @property-read mixed $translations
 * @method static \Illuminate\Database\Eloquent\Builder|File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|File query()
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDownloadCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereDownloadUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereFileVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereJsonContainsLocale(string $column, string $locale, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereJsonContainsLocales(string $column, array $locales, ?mixed $value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereLocale(string $column, string $locale)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereLocales(string $column, array $locales)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePostUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File wherePublishedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{
    use HasFactory;
    use TranslatableTrait;
    use SeoableTrait;
    use HasMediaTrait;

    protected $table = 'manage__files';

    protected $fillable = [
        'name',
        'post_url',
        'company_name',
        'download_url',
        'file_version',
        'file_size',
        'download_count',
        'required',
        'published_date',
        'description',
        'content',
        'is_active',
        'file_categories',
        'image',
        'download_code',
    ];

    public $translatable = [
        'name',
        'description',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_date' => 'datetime',
    ];

    public function getUrl()
    {
        return route('manage.web.file.detail', $this->id);
    }

    public function file_categories()
    {
        return $this->belongsToMany(FileCategory::class, 'manage__file_file_category');
    }

    public function setFileCategoriesAttribute($value)
    {
        $value = array_filter($value);

        static::saved(function ($model) use ($value) {
            $model->file_categories()->sync($value);
        });
    }

    public function setFileAttribute($value)
    {
        $this->mediaAttributes['file'] = $value;
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia('file');
    }

    public function setImageAttribute($value)
    {
        $this->mediaAttributes['image'] = $value;
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('image');
    }

    public function getRelatedFilesAttribute()
    {
        return $this->file_categories->map(function ($category) {
            return $category->files->where('id', '!=', $this->id)->take(5);
        })->flatten();
    }
}
