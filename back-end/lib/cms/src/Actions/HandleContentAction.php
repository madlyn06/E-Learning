<?php

namespace Newnet\Cms\Actions;

use Newnet\Seo\Models\Ads;
use Newnet\Seo\Models\ShortLink;
use Newnet\Cms\Interface\ContentInterface;
use DOMDocument;
use DOMXPath;

class HandleContentAction implements ContentInterface
{
    private $content;

    private $dom;

    private static $instance;

    public function action($content)
    {
        $this->content = $content;
        if (filter_var(request()->get('isAmp'), FILTER_VALIDATE_BOOLEAN)) {
            return $this
                ->replaceIframeWithAmpIframe()
                ->removeShortCode()
                ->addAttributeForIframe()
                ->fixAmpIframe()
                // ->fixAmpIframePosition()
                ->fixAmpIframeWithGenericPlaceholder()
                ->fixAmpIframeDataSrcToSrc()
                ->addSeperateIntoH2()
                // ->removeStyleTableForMobile()
                ->removeTableMarginLeftRight()
                ->getContent();
        }
        return $this
            ->renderShortLink()
            // ->renderPopupShortCodeContent()
            ->renderLinkInForButtonDownload()
            ->renderButtonShortCodeContent()
            ->addSeperateIntoH2()
            // ->removeStyleTableForMobile()
            ->removeTableMarginLeftRight()
            ->getContent();
    }

    private function renderLinkInForButtonDownload()
    {
        $pattern = '/popup_short_code\[(.*?)\]/';
        preg_match_all($pattern, $this->content, $matches);
        $short_codes = $matches[0];
        $numbers = $matches[1];

        $ads = Ads::whereIn('code', $numbers)->get();
        foreach ($ads as $key => $item) {
            $realContent = render_link_to_redirect_new_page_guide($item);
            $this->content = str_replace($short_codes[$key], $realContent, $this->content);
        }
        return $this;
    }

    public function removeTableMarginLeftRight(): self
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $newContent = mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8');
        if (empty($newContent)) {
            return $this;
        }
        $dom->loadHTML($newContent);
        libxml_clear_errors();

        $tables = $dom->getElementsByTagName('table');

        foreach ($tables as $table) {
            if ($table->hasAttribute('width')) {
                $table->removeAttribute('width');
            }
            if ($table->hasAttribute('style')) {
                $style = $table->getAttribute('style');
                $cleanedStyle = preg_replace('/\bmargin-(left|right)\s*:\s*[^;]+;?/i', '', $style);
                $cleanedStyle = preg_replace('/\bwidth\s*:\s*[^;]+;?/i', '', $cleanedStyle);
                $cleanedStyle = trim($cleanedStyle);
                if (!empty($cleanedStyle)) {
                    $table->setAttribute('style', $cleanedStyle);
                } else {
                    $table->removeAttribute('style');
                }
            }
        }

        $this->content = $dom->saveHTML();
        return $this;
    }

    public function fixAmpIframeWithGenericPlaceholder(): self
{
    $dom = new \DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    $iframes = $dom->getElementsByTagName('amp-iframe');

    foreach ($iframes as $iframe) {
        // Kiểm tra nếu đã có placeholder thì bỏ qua
        $hasPlaceholder = false;
        foreach ($iframe->childNodes as $child) {
            if ($child->nodeName === 'amp-img' && $child->hasAttribute('placeholder')) {
                $hasPlaceholder = true;
                break;
            }
        }

        if (!$hasPlaceholder) {
            $placeholderUrl = 'https://via.placeholder.com/1200x675?text=Video+Preview';

            $placeholder = $dom->createElement('amp-img');
            $placeholder->setAttribute('placeholder', '');
            $placeholder->setAttribute('layout', 'responsive');
            $placeholder->setAttribute('width', '1200');
            $placeholder->setAttribute('height', '675');
            $placeholder->setAttribute('src', $placeholderUrl);

            $iframe->appendChild($placeholder);
        }
    }

    $this->content = $dom->saveHTML();
    return $this;
}

    public function fixAmpIframePosition(): self
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $iframes = $dom->getElementsByTagName('amp-iframe');

        if ($iframes->length > 0) {
            $firstIframe = $iframes->item(0);
            $spacer = $dom->createElement('div');
            $spacer->setAttribute('style', 'height:700px;');

            $spacer = $dom->createElement('div', '&nbsp;');
            $spacer->setAttribute('style', 'height:700px; visibility: hidden;');

            $parent = $firstIframe->parentNode;

            if ($parent) {
                $parent->insertBefore($spacer, $firstIframe);
            }
        }

        $this->content = $dom->saveHTML();
        return $this;
    }

    private function replaceIframeWithAmpIframe(): static
    {
        $this->content = str_replace('<iframe', '<amp-iframe', $this->content);
        $this->content = str_replace('</iframe', '</amp-iframe', $this->content);
        return $this;
    }

    private function removeShortCode(): static
    {
        $pattern = '/<[^>]*>(?:short_code|link_short_code|popup_short_code|button_short_code)\[[^\]]+\]<\/[^>]*>/';
        $this->content = preg_replace($pattern, '', $this->content);
        return $this;
    }

    private function fixAmpIframe(): self
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $iframes = $dom->getElementsByTagName('amp-iframe');

        foreach ($iframes as $iframe) {
            if ($iframe->getAttribute('width') === '100%') {
                $iframe->setAttribute('width', '16');
                $iframe->setAttribute('height', '9');
                $iframe->setAttribute('layout', 'responsive');
            }
            if (!$iframe->hasAttribute('sandbox')) {
                $iframe->setAttribute('sandbox', 'allow-scripts allow-same-origin');
            }
            if (!$iframe->hasAttribute('frameborder')) {
                $iframe->setAttribute('frameborder', '0');
            }
        }

        $this->content = $dom->saveHTML();
        return $this;
    }

    public function fixAmpIframeDataSrcToSrc(): self
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $iframes = $dom->getElementsByTagName('amp-iframe');

        foreach ($iframes as $iframe) {
            if (!$iframe->hasAttribute('src') && $iframe->hasAttribute('data-src')) {
                $src = $iframe->getAttribute('data-src');
                $iframe->removeAttribute('data-src');
                $iframe->setAttribute('src', $src);
            }
        }

        $this->content = $dom->saveHTML();
        return $this;
    }

    private function addAttributeForIframe()
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->encoding = 'utf-8';
        $xpath = new DOMXPath($dom);
        $ampIframe = $xpath->query('//amp-iframe');
        foreach ($ampIframe as $ampIf) {
            $ampIf->setAttribute('layout', 'responsive');
            $ampIf->setAttribute('sandbox', 'allow-scripts allow-same-origin');
            $ampIf->setAttribute('placeholder', '');
            if ($ampIf->hasAttribute('loading')) {
                $ampIf->removeAttribute('loading');
            }
        }
        $this->content = $dom->saveHTML();
        return $this;
    }

    private function renderShortLink()
    {
        $pattern = '/link_short_code\[([^\]]+)\]/';
        preg_match_all($pattern, $this->content, $matches);
        $short_codes = $matches[0];
        $codes = $matches[1];
        $shortLinks = ShortLink::whereIn('code', $codes)->get()->keyBy('code');
        foreach ($codes as $index => $code) {
            if (isset($shortLinks[$code])) {
                $realContent = add_shortlink($shortLinks[$code]);
                $this->content = str_replace($short_codes[$index], $realContent, $this->content);
            }
        }
        return $this;
    }

    private function renderPopupShortCodeContent()
    {
        $pattern = '/popup_short_code\[(.*?)\]/';
        preg_match_all($pattern, $this->content, $matches);
        $short_codes = $matches[0];
        $numbers = $matches[1];

        $ads = Ads::whereIn('code', $numbers)->get();
        foreach ($ads as $key => $item) {
            $realContent = render_popup_show_code($item);
            $this->content = str_replace($short_codes[$key], $realContent, $this->content);
        }
        return $this;
    }

    private function renderButtonShortCodeContent()
    {
        $isFromGoogle = filter_var(request('isFromGoogle'), FILTER_VALIDATE_BOOLEAN);
        $pattern = '/button_short_code\[(.*?)\]/';
        preg_match_all($pattern, $this->content, $matches);
        $short_codes = $matches[0];
        $numbers = $matches[1];

        $ads = Ads::whereIn('code', $numbers)->get();
        foreach ($ads as $key => $item) {
            $newContent = render_button_show_code($item, $isFromGoogle);
            $this->content = str_replace($short_codes[$key], $newContent, $this->content);
        }
        return $this;
    }

    private function addSeperateIntoH2()
    {
        $dom = new DOMDocument();
        $newContent = mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8');
        if (empty($newContent)) {
            return $this;
        }
        @$dom->loadHTML($newContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->encoding = 'utf-8';
        $xpath = new DOMXPath($dom);
        $h2_elements = $xpath->query('//h2');
        foreach ($h2_elements as $h2) {
            if ($h2->nodeValue !== '') {
                $h2->setAttribute('style', 'font-weight: bold;');
                $h2->setAttribute('class', 'sidebar__widget-title underline-h2');
            }
        }
        $this->content = $dom->saveHTML();
        return $this;
    }

    private function removeStyleTableForMobile()
    {
        $isMobile = filter_var(request('isMobile'), FILTER_VALIDATE_BOOLEAN);
        $xpath = $this->getDOMDocumentInstance();
        if ($isMobile) {
            $tables = $xpath->query('//table');
            foreach ($tables as $table) {
                $table->removeAttribute('style');
                $table->removeAttribute('width');
            }
        }
        $tds = $xpath->query('//td');
        foreach ($tds as $td) {
            $td->setAttribute('style', 'padding-left: 10px;padding-top: 10px;padding-right: 10px;border-color: #3e4073');
        }
        $this->content = $this->dom->saveHTML();
        return $this;
    }

    private function getDOMDocumentInstance()
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($this->content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->encoding = 'utf-8';
        $xpath = new DOMXPath($dom);
        if (!self::$instance) {
            self::$instance = $xpath;
        }
        $this->dom = $dom;
        return self::$instance;
    }

    private function getContent()
    {
        return $this->content;
    }
}
