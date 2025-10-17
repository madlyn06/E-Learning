<?php

namespace Newnet\Cms\Factory;

use Newnet\Cms\Enums\ActionEnum;
use Newnet\Cms\Exceptions\CrawlDataException;
use Newnet\Cms\Handlers\MergeCommandHandler;
use Newnet\Cms\Handlers\TranslateCommandHandler;

class CommandHandlerFactory
{
    public static function create(string $type)
    {
        switch ($type) {
            case ActionEnum::REWRITE->value:
                return new MergeCommandHandler();
            case ActionEnum::TRANSLATE->value:
                return new TranslateCommandHandler();
            default:
                throw new CrawlDataException('Command hander not found type: ' . $type);
        }
    }
}
