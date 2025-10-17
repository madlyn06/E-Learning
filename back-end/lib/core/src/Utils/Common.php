<?php

namespace Newnet\Core\Utils;

class Common
{
  public static function buildSlug($url)
  {
    $enableHtmlSuffix = setting('enable_html_suffix');
    $urlArr = explode('/', $url);
    $slug = $urlArr[count($urlArr) - 1];
    if ($enableHtmlSuffix) {
      $slug = $slug . '.html';
    }
    return $slug;
  }

  public static function convertSlug($slug)
  {
    $enableHtmlSuffix = setting('enable_html_suffix');
    if ($enableHtmlSuffix) {
      return str_replace('.html', '', $slug);
    }
    return $slug;
  }

  public static function buildCategoryTree($items, $parentId = null)
  {
    $dataTree = [];
    foreach ($items as $cat) {
      if ($cat->parent_id == $parentId) {
        $data = [
          'title' => $cat->name,
          'key' => $cat->id,
        ];
        if ($cat->hasChildren()) {
          $data['children'] = self::buildCategoryTree($cat->children()->get(), $cat->id);
        }
        $dataTree[] = $data;
      }
    }
    return $dataTree;
  }

  /**
   * Build menu tree
   */
  public static function buildMenuTree($items, $parentId = null)
  {
    $dataTree = [];
    foreach ($items as $item) {
      if ($item->parent_id == $parentId) {
        $data = [
          'label' => $item->label,
          'key' => $item->id,
          'url' => $item->menu_builder_class == 'Newnet\Cms\MenuBuilders\CategoryMenuBuilder' ? self::addCategoryPrefix($item->url) : $item->url,
          'icon' => $item->icon,
          'type' => $item->menu_builder_class == 'Newnet\Cms\MenuBuilders\CategoryMenuBuilder' ? 'category' : null
        ];
        if ($item->hasChildren()) {
          $data['children'] = self::buildMenuTree($item->children()->get(), $item->id);
        }
        $dataTree[] = $data;
      }
    }
    return $dataTree;
  }

  private static function addCategoryPrefix($url)
  {
    if ($url == '#') {
      return $url;
    }
    $parsed = parse_url($url);

    $hostPart = $parsed['scheme'] . '://' . $parsed['host'] .
      (isset($parsed['port']) ? ':' . $parsed['port'] : '');

    $path = $parsed['path'];
    if (strpos($path, '/danh-muc/') === 0) {
      return $url;
    }

    $newPath = '/danh-muc' . $path;

    return $hostPart . $newPath;
  }

  /**
   * Build menu tree
   */
  public static function buildCommentTree($items, $parentId = null)
  {
    $dataTree = [];
    foreach ($items as $item) {
      if ($item->parent_id == $parentId) {
        $data = [
          'name' => $item->name,
          'content' => $item->content,
          'key' => $item->id,
        ];
        if ($item->hasChildren()) {
          $data['children'] = self::buildCommentTree($item->children()->get(), $item->id);
        }
        $dataTree[] = $data;
      }
    }
    return $dataTree;
  }
}
