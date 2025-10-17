<?php

namespace Newnet\Cms\Actions;

class DocumentationRenderAction
{
  protected $items = [];

  public function all()
  {
    return $this->items;
  }

  public function add($uri, $key, $label, $view = null)
  {
    $this->items[$key] = [
      'uri'   => $uri,
      'key'   => $key,
      'label' => $label,
      'view'  => $view,
    ];

    return $this;
  }

  public function getLabel($key)
  {
    return $this->items[$key]['label'] ?? ucfirst($key);
  }

  public function getView($key)
  {
    return $this->items[$key]['view'] ?? $key;
  }

  public function render()
  {
    $items = $this->items;
    $html = '';
    foreach ($items as $item) {
      $html .= view($item['view'], compact('item'))->render();
    }
    return $html;
  }
}
