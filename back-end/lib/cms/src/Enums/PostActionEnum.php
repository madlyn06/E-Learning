<?php

namespace Newnet\Cms\Enums;

enum PostActionEnum: string
{
    case PUBLISH = 'PUBLISH';
    case DRAFT = 'DRAFT';
    case SCHEDULE = 'SCHEDULE';

    public static function getValues(): array
    {
        return [
            self::DRAFT->value,
            self::PUBLISH->value,
            self::SCHEDULE->value,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::DRAFT->value => 'Lưu vào bản nháp',
            self::PUBLISH->value => 'Tự động đăng bài viết',
            self::SCHEDULE->value => 'Tự động đăng vào thời gian',
        ];
    }

    public static function getLabel(string $value): string
    {
        return self::getLabels()[$value];
    }
}
