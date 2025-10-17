<?php

namespace Newnet\Cms\Actions;

use DOMDocument;
use DOMXPath;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Models\Story;

class InsertStoryToPostAction
{
  public static function insert(Post $post, Story $story)
  {
    $postionInsert = (int) setting('story_insert_to');
    $code = '[story_code="'.$story->slug.'"]';
    $content = $post->content;
    switch ($postionInsert) {
      case 0:
      $content = $code . $content;
        break;
      case 1:
        $contentHtml = '<html><body>' . $post->content . '</body></html>';
        $doc = new DOMDocument();
        $doc->loadHTML($contentHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new DOMXPath($doc);
        $firstH2 = $xpath->query('//h2')->item(0);
        if ($firstH2) {
          $newTextNode = $doc->createTextNode($code);
          $firstH2->parentNode->insertBefore($newTextNode, $firstH2->nextSibling);
          $content = $doc->saveHTML();
        }
        break;
      default:
        $content = $content. $code;
        break;
    }
    $post->update(['content' => $content, 'is_created_story' => true]);
  }
}
