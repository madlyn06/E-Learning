<?php

use Newnet\Media\Repositories\MediaRepositoryInterface;

if (!function_exists('get_media')) {
    /**
     * Get Media
     *
     * @param $mediaId
     *
     * @return \Newnet\Media\Models\Media|null
     */
    function get_media($mediaId)
    {
        if ($mediaId) {
            return app(MediaRepositoryInterface::class)->find($mediaId);
        }

        return null;
    }
}

if (!function_exists('imageProxy')) {
    function imageProxy($url, $width, $height = null, $format = 'jpg', $quality = 80)
    {
        return Img::url($url, $width, $height, $quality);
    }
}

if (!function_exists('get_providers_cloud'))
{
    function get_cloud_providers()
    {
        $providers = [
            'digital_ocean' => 'Digital Ocean',
        ];
        foreach ($providers as $key => $item) {
            $options[] = [
                'value' => $key,
                'label' => trim($item),
            ];
        }

        return $options;
    }
}
