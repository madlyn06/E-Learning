<?php

namespace Newnet\Cms\Utils;

class StringUtils
{
  /**
   * @param string
   * @return array
   */
  public static function convertContentShortCode(string $content)
  {
    $pattern = '/\[story_code="([^"]+)"\]/';
    preg_match_all($pattern, $content, $matches);

    return [
      'pattern' => $matches[0],
      'code' => $matches[1],
    ];
  }

  /**
   * @param string $content
   * @return string
   */
  public static function replaceImgElement(string $content)
  {
    $pattern = '/<img [^>]*?src="([^"]+)/i';
    $prepend_url = config('app.url');
    // Replace img src attributes
    return preg_replace_callback($pattern, function ($match) use ($prepend_url) {
      $src = $match[0];
      // Check if src doesn't start with 'https://'
      if (!preg_match('~\bsrc=("|\')https://~i', $src)) {
        // Prepend $prepend_url to src
        $src = preg_replace('~\bsrc=("|\')~i', 'src="' . $prepend_url . '', $src);
      }
      return $src;
    }, $content);
  }

  public static function replaceImgToAmpImgTag($content)
  {
    return str_replace('<img', '<amp-img', $content);
  }

  /**
   * Cut the string from the position of the first occurrence
   * of ANY character in the $specialChars array.
   * For example, if it finds '-' or '|', it will remove everything after that character.
   *
   * @param string $title        The original string.
   * @param array  $specialChars An array of characters to search for.
   * @return string              The trimmed string before any of the specified characters.
   */
  public static function cutStringAtFirstOccurrence($title, array $specialChars)
  {
    // Default to false if no character is found
    $pos = false;

    foreach ($specialChars as $char) {
      $foundPos = strpos($title, $char);
      // If $char is found in $title
      // and (we don't have a position yet, or the found position is smaller than the current one),
      // update $pos to the found position.
      if ($foundPos !== false && ($pos === false || $foundPos < $pos)) {
        $pos = $foundPos;
      }
    }

    // If we found any position
    if ($pos !== false) {
      // Cut the string from the beginning to right before $pos
      $title = substr($title, 0, $pos);
    }

    return trim($title);
  }
}
