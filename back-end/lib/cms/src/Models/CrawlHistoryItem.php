<?php

namespace Newnet\Cms\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Newnet\Cms\Models\CrawlHistory
 *
 * @property int $id
 * @property string $url
 * @property string|null $origin_title
 * @property string|null $title
 * @property string|null $origin_content
 * @property string|null $content
 * @property string|null $status
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereOriginContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereOriginTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereUrl($value)
 * @property string|null $file_name
 * @property string|null $handled_file_name
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereTranslatedFileName($value)
 * @property string|null $origin_file_name
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereIsCreatedPost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereOriginFileName($value)
 * @property int $crawl_history_id
 * @property string|null $name The title after translated
 * @property-read \Newnet\Cms\Models\CrawlHistory|null $history
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistoryItem whereCrawlHistoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistoryItem whereName($value)
 * @property-read \Newnet\Cms\Models\CrawlHistory $crawlHistory
 * @property \Illuminate\Support\Carbon|null $published_at
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistoryItem whereHandledFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistoryItem wherePublishedAt($value)
 * @mixin \Eloquent
 */
class CrawlHistoryItem extends Model
{
    protected $table = 'cms__crawl_history_items';

    protected $fillable = [
        'crawl_history_id',
        'url',
        'origin_title',
        'name',
        'origin_file_name',
        'handled_file_name',
        'status',
        'message',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function crawlHistory()
    {
        return $this->belongsTo(CrawlHistory::class, 'crawl_history_id');
    }
}
