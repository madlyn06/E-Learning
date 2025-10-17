<?php

namespace Newnet\Cms\Actions;

use DOMDocument;
use DOMXPath;

class AddSeperateIntoH2Action
{
  public static function add($content)
  {
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $dom->encoding = 'utf-8';

    $xpath = new DOMXPath($dom);
    $h2_elements = $xpath->query('//h2');
    foreach ($h2_elements as $h2) {
      $newElement = $dom->createElement('div', '');
      $newElement->setAttribute('class', 'dlab-separator style-2 bg-primary');

      $h2->parentNode->insertBefore($newElement, $h2->nextSibling);
    }
    return $dom->saveHTML();
  }
}
