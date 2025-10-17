<?php

namespace Newnet\Cms\Traits;

use Newnet\Seo\Repositories\InternalLinkRepositoryInterface;

/**
 * Trait HasAppendInternalLinkTrait
 *
 * @package Newnet\Cms\Traits
 */
trait HasAppendInternalLinkTrait
{
  protected static function bootHasAppendInternalLinkTrait()
  {
    static::saved(function (self $model) {
      if (!empty(request()->all()['append_internal_link'])) {
        $model->attachInternalLinks($model);
      } else {
        $model->detachInternalLinks($model);
      }
    });
  }

  public function attachInternalLinks($model)
  {
    $keywords = app(InternalLinkRepositoryInterface::class)->all(['name', 'value']);
    $content = $this->content;
    $processedPhrases = [];

    foreach ($keywords as $item) {
      if (strpos($content, $item->name) !== false && !in_array($item->name, $processedPhrases)) {
        $content = preg_replace('/' . preg_quote($item['name'], '/') . '/', '<a href="' . $item->value . '">' . $item->name . '</a>', $content, 1);
        $processedPhrases[] = $item->name;
      }
    }
    $model->content = $content;
    $model->saveQuietly();
  }

  public function detachInternalLinks($model)
  {
    $keywords = app(InternalLinkRepositoryInterface::class)->all(['name', 'value']);
    $content = $this->content;
    foreach ($keywords as $keyword) {
      $content = str_replace("<a href=\"$keyword->value\"> $keyword->name </a>", $keyword->name, $content);
    }
    $model->content = $content;
    $model->saveQuietly();
  }

  private function getSetting()
  {
    return setting('is_append_all_internal_links', false);
  }
}
