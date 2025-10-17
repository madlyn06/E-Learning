<?php

namespace Newnet\Cms\Enums;

enum CrawlHistoryEnum: string
{
    case RUNNING = 'RUNNING';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';

    public static function getValues(): array
    {
        return [
            self::RUNNING->value,
            self::COMPLETED->value,
            self::FAILED->value,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::RUNNING->value => 'Đang xử lý...',
            self::COMPLETED->value => 'Đã cào và rewrite thành công.',
            self::FAILED->value => 'Thất bại.',
        ];
    }

    public static function getLabel(string $value): string
    {
        return self::getLabels()[$value];
    }
}
