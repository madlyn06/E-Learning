<?php

namespace Newnet\Cms\Enums;

enum CrawlHistoryItemEnum: string
{
    case ALREADY_CRAWL = 'ALREADY_CRAWL';
    case PENDING = 'PENDING';
    case CRAWLING = 'CRAWLING';
    case CRAWLED = 'CRAWLED';
    case REWRITING = 'REWRITING';
    case REWROTE = 'REWROTE';
    case PUBLISHED = 'PUBLISHED';
    case DRAFT = 'DRAFT';
    case FAILED = 'FAILED';

    public static function getValues(): array
    {
        return [
            self::ALREADY_CRAWL->value,
            self::PENDING->value,
            self::CRAWLING->value,
            self::CRAWLED->value,
            self::REWRITING->value,
            self::REWROTE->value,
            self::PUBLISHED->value,
            self::DRAFT->value,
            self::FAILED->value,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::ALREADY_CRAWL->value => 'URL đã được cào.',
            self::PENDING->value => 'Đang chờ...',
            self::CRAWLING->value => 'Đang cào dữ liệu...',
            self::CRAWLED->value => 'Đã cào thành công.',
            self::REWRITING->value => 'Đang rewrite bài viết...',
            self::REWROTE->value => 'Đã rewrite xong.',
            self::PUBLISHED->value => 'Đã rewrite xong và publish bài viết.',
            self::DRAFT->value => 'Đã rewrite xong và lưu vào nháp.',
            self::FAILED->value => 'Rewrite thất bại.',
        ];
    }

    public static function getLabel(string $value): string
    {
        return self::getLabels()[$value];
    }
}
