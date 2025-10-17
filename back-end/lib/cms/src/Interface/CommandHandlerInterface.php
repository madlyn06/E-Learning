<?php

namespace Newnet\Cms\Interface;

use Newnet\Cms\Models\CrawlHistory;

interface CommandHandlerInterface
{
    public function execute(CrawlHistory $crawlHistory);
}
