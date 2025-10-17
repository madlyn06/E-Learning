<?php

namespace Newnet\Cms\Console\Commands;

use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Newnet\Cms\Models\Post;
use Newnet\Seo\Repositories\InternalLinkRepositoryInterface;

class UpdateInternalLinkCommand extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'dnsoft:update-internal-link {--apply-all=}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update the internal link with the post has not been processed';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle(): void
  {
    $applyAll = $this->option('apply-all');
    $keywords = app(InternalLinkRepositoryInterface::class)->all(['id', 'name', 'value'])->sortDesc();
    if ($applyAll === 'true' || $applyAll === true || $applyAll === 1) {
      $posts = Post::orderBy('id', 'desc');
    } else {
      $posts = Post::where('append_internal_link', false)->orderBy('id', 'desc');
    }
    $posts->chunk(100, function ($posts) use ($keywords) {
      foreach ($posts as $post) {
        $content = $post->content;
        $decoded_data = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Perform the keyword replacement on the entire content
        foreach ($keywords as $item) {
          if (stripos($decoded_data, $item->name) !== false) {
            $link = '<a target="_blank" href="' . $item->value . '">' . $item->name . '</a>';
            $content = preg_replace('/' . preg_quote($item->name, '/') . '/i', $link, $decoded_data);
          }
        }

        // Load the HTML content into DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Remove internal links inside h1, h2, h3 tags
        $xpath = new DOMXPath($dom);
        foreach (['h1', 'h2', 'h3'] as $header) {
          $nodes = $xpath->query("//{$header}//a");
          foreach ($nodes as $node) {
            $parent = $node->parentNode;
            while ($node->firstChild) {
              $parent->insertBefore($node->firstChild, $node);
            }
            $parent->removeChild($node);
          }
        }

        // Save the modified content
        $contentWithPlaceholders = $dom->saveHTML();

        // Load the modified content into DOMDocument again to process duplicate links
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contentWithPlaceholders, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Remove duplicate links
        $xpath = new DOMXPath($dom);
        foreach ($keywords as $item) {
          $nodes = $xpath->query("//a[contains(text(), '{$item->name}')]");
          $firstNode = true;
          foreach ($nodes as $node) {
            if ($firstNode) {
              $firstNode = false;
            } else {
              $parent = $node->parentNode;
              while ($node->firstChild) {
                $parent->insertBefore($node->firstChild, $node);
              }
              $parent->removeChild($node);
            }
          }
        }

        // Save the final content
        $finalContent = $dom->saveHTML();
        $post->content = $finalContent;
        $post->save();

        $this->info('Appended internal links post ID = ' . $post->id);
      }
    });
  }
}
