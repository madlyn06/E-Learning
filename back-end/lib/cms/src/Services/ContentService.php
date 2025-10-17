<?php

namespace Newnet\Cms\Services;

use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Intervention\Image\Facades\Image;
use Newnet\Cms\Actions\HandleContentListableAction;
use Newnet\Media\MediaUploader;
use Newnet\Cms\Models\Post;
use Newnet\Cms\Factory\CommandHandlerFactory;
use Newnet\Cms\Models\CrawlHistory;

class ContentService
{
    public function dispatch(Post $post)
    {
        HandleContentListableAction::action($post);
        $medias = $this->handleImagesInContent($post);
        if (!empty($medias) > 0) {
            // Set thumbnail image
            $media = $medias[0];
            $post->media()->attach($media->id, [
                'group' => 'image',
            ]);
        }
    }

    /**
     * Handle images in the content
     */
    private function handleImagesInContent($post)
    {
        $htmlContent = $post->content;
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $dom->encoding = 'utf-8';
        $images = $dom->getElementsByTagName('img');

        $medias = [];
        foreach ($images as $img) {
            $src = $img instanceof \DOMElement ? $img->getAttribute('data-src') : '';
            if (empty($src)) {
                $src = $img instanceof \DOMElement ? $img->getAttribute('src') : '';
            }
            if (!empty($src)) {
                $media = $this->handleConvertUrlToMedia($src, $post->name);
                if ($media) {
                    $medias[] = $media;
                    $img->setAttribute('src', $media->url);
                    $imgDataSrc = $img instanceof \DOMElement ? $img->getAttribute('data-src') : '';
                    if (!empty($imgDataSrc)) {
                        $img->setAttribute('data-src', $media->url);
                    }
                }
            }
        }

        $post->content = $dom->saveHTML();
        $post->save();
        return $medias;
    }

    /**
     * This function convert URL to media object
     */
    public function handleConvertUrlToMedia($imageUrl, $imageName)
    {
        $isAllowConvertToWebp = setting('allow_convert_image_to_webp', false);
        $isAllowRotateImage180 = setting('allow_rotate_image_180', false);
        $isAllowResizeTo1200x675 = setting('resize_image_to_1200x675', false);

        $settings = [
            'allowResize' => $isAllowResizeTo1200x675,
            'allowRotate' => $isAllowRotateImage180,
            'allowWebp' => $isAllowConvertToWebp,
        ];
        try {
            $response = Http::get($imageUrl);
            if (!$response->successful()) {
                return null;
            }
            $imageContent = $response->body();

            $defaults = [
                'allowResize' => true,
                'width'       => 1200,
                'height'      => 675,
                'allowRotate' => false,
                'allowWebp'   => false,
                'quality'     => 90,
            ];

            $settings = array_merge($defaults, $settings);
            $image = Image::make($imageContent);
            
            // 1. Rotate image horizontal
            if ($settings['allowRotate']) {
                $image->flip('horizontal');
            }

            // 2. Encode image to WebP
            if ($settings['allowWebp']) {
                $extension = 'webp';
                $mime = 'image/webp';
                $image = $image->encode($extension, $settings['quality']);
            } else {
                // Keep the original extension
                // Use the pathinfo to get the extension from the URL
                $path = parse_url($imageUrl, PHP_URL_PATH);
                $extensionFromUrl = pathinfo($path, PATHINFO_EXTENSION);
                $extensionFromUrl = strtolower($extensionFromUrl);

                // If the extension is not valid, fallback to MIME type
                $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp'];
                if (!in_array($extensionFromUrl, $validExtensions)) {
                    // Get mine type and map to extension
                    $mime = $image->mime();
                    $extensionMap = [
                        'image/jpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif',
                        'image/bmp'  => 'bmp',
                        'image/tiff' => 'tiff',
                        'image/webp' => 'webp',
                    ];
                    $extension = isset($extensionMap[$mime]) ? $extensionMap[$mime] : 'png'; // Default to PNG
                } else {
                    // Extension from URL is valid
                    $extension = $extensionFromUrl;
                    $mime = $image->mime();
                }

                // Encode image to original format with appropriate quality
                switch ($mime) {
                    case 'image/jpeg':
                        $image = $image->encode('jpg', 100); // Quantity 100 for JPEG (0-100)
                        break;
                    case 'image/png':
                        $image = $image->encode('png', 9); // Quantity 9 for PNG (0-9)
                        break;
                    case 'image/gif':
                        $image = $image->encode('gif'); // GIF without quality parameter
                        break;
                    case 'image/bmp':
                        $image = $image->encode('bmp'); // BMP without quality parameter
                        break;
                    case 'image/tiff':
                        $image = $image->encode('tiff'); // TIFF without quality parameter
                        break;
                    case 'image/webp':
                        $image = $image->encode('webp', $settings['quality']); // Quantity from settings
                        break;
                    default:
                        // If not determined, default to PNG
                        $image = $image->encode('png', 100);
                        $mime = 'image/png';
                        $extension = 'png';
                        break;
                }
            }

            // 3. Save image to temp file
            $fileName = Str::slug($imageName) . '-' . uniqid() . '.' . $extension;
            $tempImagePath = sys_get_temp_dir() . '/' . $fileName;

            // 4. Resize image to 1200x675
            if ($settings['allowResize']) {
                $originalWidth = $image->width();
                $originalHeight = $image->height();
                $background = Image::canvas(1200, 675, '#ffffff');
                if ($originalWidth > 1200 || $originalHeight > 675) {
                    $image->fit(1200, 675, function ($constraint) {
                        $constraint->upsize();
                    });
                    $background = $image;
                } else {
                    $image->resize(1200, 675, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $background->insert($image, 'center');
                }
            }
            $background->save($tempImagePath, 90);

            $uploadedFile = new UploadedFile($tempImagePath, $fileName, $mime, null, true);
            return app(MediaUploader::class)->setFile($uploadedFile)->upload();
        } catch (\Throwable $th) {
            logger('Error when convert image to media: ', ['url' => $imageUrl, 'message' => $th->getMessage()]);
            return null;
        }
    }

    /**
     * Handles the replacement of words in a given post.
     *
     * @param string $replaceWords An associative array where the key is the word to be replaced and the value is the word to replace with.
     * @return string
     */
    public function handleReplaceWords($content, $replaceWords)
    {
        $pairs = explode(',', $replaceWords);
        $patterns = [];
        $replacements = [];

        foreach ($pairs as $pair) {
            $pair = trim($pair);
            $parts = explode('|', $pair);
            if (count($parts) == 2) {
                $patterns[] = '/\b' . preg_quote(trim($parts[0]), '/') . '\b/';
                $replacements[] = trim($parts[1]);
            }
        }
        return preg_replace($patterns, $replacements, $content);
    }

    /**
     * Handle content of a given CrawlHistory.
     *
     * @param CrawlHistory $history The history to be handled.
     * @return void
     */
    public function handleContent(CrawlHistory $history)
    {
        $handler = CommandHandlerFactory::create($history->purpose_action);
        /** @var CommandInvokerService */
        $commandeInvorker = app(CommandInvokerService::class);
        $commandeInvorker->setData($history);
        $commandeInvorker->invoke($handler);
    }
}
