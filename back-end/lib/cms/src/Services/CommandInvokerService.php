<?php

namespace Newnet\Cms\Services;

use Newnet\Cms\Interface\CommandHandlerInterface;
use Newnet\Cms\Models\CrawlHistory;

class CommandInvokerService
{
    protected CrawlHistory $history;

    public function setData(CrawlHistory $history)
    {
        $this->history = $history;
    }

    public function invoke(CommandHandlerInterface $command)
    {
        $command->execute($this->history);
    }
}
