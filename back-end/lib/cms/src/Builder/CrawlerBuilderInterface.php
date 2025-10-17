<?php

namespace Newnet\Cms\Builder;

interface CrawlerBuilderInterface
{
  public function crawlCategories();
  public function crawlTags();
  public function crawlPosts();
  public function crawl();
}
