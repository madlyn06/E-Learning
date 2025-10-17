<?php

namespace Newnet\Cms\Enums;

enum ActionEnum: string
{
    case REWRITE = 'REWRITE';
    case TRANSLATE = 'TRANSLATE';

    public static function getValues(): array
    {
        return [
            self::REWRITE->value,
            self::TRANSLATE->value,
        ];
    }

    public static function getLabels(): array
    {
        return [
            self::REWRITE->value => 'Viết lại thành một bài viết (Rewrite)',
            self::TRANSLATE->value => 'Dịch từng URL ra tiếng việt',
        ];
    }

    public static function getLabel(string $value): string
    {
        return self::getLabels()[$value];
    }
}
