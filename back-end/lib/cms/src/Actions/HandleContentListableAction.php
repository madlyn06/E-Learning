<?php

namespace Newnet\Cms\Actions;

use Illuminate\Support\Str;
use DOMDocument;
use DOMXPath;
use Newnet\Cms\Models\ContentList;
use Newnet\Cms\Models\Post;

class HandleContentListableAction
{
  /**
   * Main action to handle the add id into h2, h3 and add it into the content list automatically
   * @param Post $post
   */
  public static function action(Post $post)
  {
    $content = $post->content;
    if (!$content) {
      return;
    }
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $dom->encoding = 'utf-8';

    $xpath = new DOMXPath($dom);
    $h2_elements = $xpath->query('//h2');
    if (count($h2_elements) < 3) {
      return;
    }
    self::deleteAllContentList($post);
    $postWithNewContent = self::addIdToH2H3InContent($post);
    self::addContentList($postWithNewContent);
  }

  /**
   * Delete all the content list of the post before insert new
   * @param Post $post
   * @return void
   */
  private static function deleteAllContentList($post)
  {
    ContentList::where(['post_id' => $post->id])->delete();
  }

  /**
   * Update the content of a post by adding the id into the h2, h3
   * @param Post $post
   * @return Post $post
   */
  private static function addIdToH2H3InContent($post)
  {
    $content = $post->content;
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $dom->encoding = 'utf-8';

    $xpath = new DOMXPath($dom);
    $h2_elements = $xpath->query('//h2');
    $h3_elements = $xpath->query('//h3');
    $h4_elements = $xpath->query('//h4');
    $h5_elements = $xpath->query('//h5');
    $h6_elements = $xpath->query('//h6');

    // Loop through each h2 element and output its content
    foreach ($h2_elements as $h2) {
      $id = Str::slug($h2->nodeValue);
      $h2->setAttribute('id', $id);
      $h2->setAttribute('class', 'content-item');
      $h2->setAttribute('style', 'font-size: 1.55rem;');
    }
    foreach ($h3_elements as $h3) {
      $id = Str::slug($h3->nodeValue);
      $h3->setAttribute('id', $id);
      $h3->setAttribute('class', 'content-item');
      $h3->setAttribute('style', 'font-size: 1.35rem');
    }
    foreach ($h4_elements as $h4) {
      $h4->setAttribute('style', 'font-size: 1.25rem');
    }
    foreach ($h5_elements as $h5) {
      $h5->setAttribute('style', 'font-size: 1.15rem');
    }
    foreach ($h6_elements as $h6) {
      $h6->setAttribute('style', 'font-size: 1.05rem');
    }
    $html = $dom->saveHTML();
    $newContent = html_entity_decode($html, ENT_COMPAT, 'UTF-8');
    $post->update(['content' => $newContent,]);
    return $post;
  }

  /**
   * Add content into the content list
   */
  private static function addContentList($post)
  {
    $content = $post->content;
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $dom->encoding = 'utf-8';

    $current_h2 = null;

    $parentItem = null;

    // Loop through each element
    foreach ($dom->getElementsByTagName('*') as $element) {
      if ($element->nodeName == 'h2') {
        $current_h2 = $element;
        if ($current_h2->nodeValue != '""') {
          $parentItem = ContentList::create([
            'name' => $current_h2->nodeValue,
            'target' => $current_h2->getAttribute('id'),
            'post_id' => $post->id,
          ]);
        }
      } elseif ($element->nodeName == 'h3' && $current_h2 !== null) {
        if (!empty($parentItem) && $element->nodeValue != '""') {
          ContentList::create([
            'name' => $element->nodeValue,
            'target' => $element->getAttribute('id'),
            'parent_id' => $parentItem->id,
            'post_id' => $post->id,
            'created_at' => now(),
            'updated_at' => now(),
          ]);
        }
      }
    }
  }
}
