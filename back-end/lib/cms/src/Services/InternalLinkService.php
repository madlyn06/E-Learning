<?php

namespace Newnet\Cms\Services;

class InternalLinkService
{
  /**
   * @param string $content
   * @param array $keywords
   * @return string new content
   */
  public function linkify(string $content, array $keywords): string
  {
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    foreach ($keywords as $item) {
      $keyword = $item['name']['vi'];
      $url = $item['value'];
      // Replace the first occurrence only
      $content = preg_replace(
        '/\b(' . preg_quote($keyword, '/') . ')\b/i',
        '<a href="' . e($url) . '">$1</a>',
        $content,
        1
      );
    }
    return $content;
  }

   /**
   * Converts keywords in the given content to links, except within headings.
   *
   * @param string $content The content in which to convert keywords to links.
   * @param array $keywords An array of keywords to be converted to links.
   * @return string The content with keywords converted to links, except within headings.
   */
  public function linkifyExceptHeadings(string $content, array $keywords): string
  {
    // Giải mã entity HTML trước
    $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

    // Tạo DOMDocument
    $dom = new \DOMDocument();
    // Tắt các lỗi cảnh báo do HTML không well-formed
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    $xpath = new \DOMXPath($dom);

    // Lấy tất cả các text node không nằm trong h1-h6
    // Xpath: chọn tất cả node text không phải là descendant của h1,h2,h3,h4,h5,h6
    // $textNodes = $xpath->query('//text()[not(ancestor::h1) and not(ancestor::h2) and not(ancestor::h3) and not(ancestor::h4) and not(ancestor::h5) and not(ancestor::h6)]');
    $textNodes = $xpath->query('//text()[
      not(ancestor::h1) 
      and not(ancestor::h2) 
      and not(ancestor::h3) 
      and not(ancestor::h4) 
      and not(ancestor::h5) 
      and not(ancestor::h6)
      and not(ancestor::a)
  ]');
    // Lặp qua mỗi text node và thay thế từ khoá
    foreach ($textNodes as $textNode) {
      $originalText = $textNode->nodeValue;
      $newText = $this->replaceKeywords($originalText, $keywords);
      if ($newText !== $originalText) {
        // Cập nhật text node
        $textNode->nodeValue = $newText;
      }
    }

    return $dom->saveHTML();
  }

  private function replaceKeywords(string $text, array $keywords): string
  {
    foreach ($keywords as $item) {
      $keyword = $item['name']['vi'];
      $url = $item['value'];

      // Chỉ thay thế lần xuất hiện đầu tiên
      $text = preg_replace(
        '/\b(' . preg_quote($keyword, '/') . ')\b/i',
        '<a target="_blank" href="' . e($url) . '">$1</a>',
        $text,
        1
      );
    }
    return $text;
  }
}
