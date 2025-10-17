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
 * @property string $urls
 * @property string|null $prompt
 * @property string $post_action
 * @property \Illuminate\Support\Carbon|null $schedule_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Newnet\Cms\Models\CrawlHistoryItem> $crawlHistoryItems
 * @property-read int|null $crawl_history_items_count
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory wherePostAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereScheduleAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereUrls($value)
 * @property string|null $error_message
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereClassesExcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereElementsExcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereIdsExcept($value)
 * @property string|null $replace_words_before
 * @property string|null $categories
 * @property int $max_words
 * @property string|null $css_selectors
 * @property bool $is_rewrite_title
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereCssSelectors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereIsRewriteTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereMaxWords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereReplaceWordsBefore($value)
 * @property string $purpose_action
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory wherePurposeAction($value)
 * @property string|null $css_selectors_except
 * @property string|null $title_prompt
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereCssSelectorsExcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlHistory whereTitlePrompt($value)
 * @mixin \Eloquent
 */
class CrawlHistory extends Model
{
    protected $table = 'cms__crawl_histories';

    protected $fillable = [
        'urls',
        'prompt',
        'post_action',
        'purpose_action',
        'schedule_at',
        'css_selectors_except',
        'title_prompt',
        'status',
        'error_message',
        'replace_words_before',
        'max_words',
        'css_selectors',
        'is_rewrite_title',
        'categories',
    ];

    protected $casts = [
        'schedule_at' => 'datetime',
        'is_rewrite_title' => 'boolean',
    ];

    public function crawlHistoryItems()
    {
        return $this->hasMany(CrawlHistoryItem::class, 'crawl_history_id');
    }
}
