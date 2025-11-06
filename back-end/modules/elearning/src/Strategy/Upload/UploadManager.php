<?php

declare(strict_types=1);

namespace Modules\Elearning\Strategy\Upload;

use Illuminate\Support\Manager;
use Modules\Elearning\Interfaces\PresignedUrlGenerator;
use Modules\Elearning\Strategy\Upload\S3Presigner;
use Modules\Elearning\Strategy\Upload\DigitalOceanPresigner;
use Modules\Elearning\Strategy\Upload\BunnyPresigner;
use Modules\Elearning\Strategy\Upload\ServerIntent;
use Aws\S3\S3Client;

class UploadManager extends Manager
{
    public function getDefaultDriver()
    {
        return setting('video_provider');
    }

    public function createS3Driver(): PresignedUrlGenerator
    {
        $s3 = new S3Client([
            'version'     => '2006-03-01',
            'region'      => config('filesystems.disks.s3.region'),
            'credentials' => [
                'key'    => config('elearning.s3_config.access_key_id'),
                'secret' => config('elearning.s3_config.secret_access_key'),
            ],
        ]);

        return new S3Presigner(
            $s3,
            config('elearning.s3_config.bucket'),
            rtrim(config('elearning.s3_config.cdn_base_url', ''), '/')
        );
    }

    public function createDigitaloceanDriver(): PresignedUrlGenerator
    {
        $s3 = new S3Client([
            'version'     => '2006-03-01',
            'region'      => config('elearning.digitalocean_config.region'),
            'endpoint'    => rtrim(config('elearning.digitalocean_config.endpoint'), '/'),
            'use_path_style_endpoint' => false,
            'credentials' => [
                'key'    => config('elearning.digitalocean_config.access_key_id'),
                'secret' => config('elearning.digitalocean_config.secret_access_key'),
            ],
        ]);

        return new DigitalOceanPresigner(
            $s3,
            config('elearning.digitalocean_config.bucket'),
            rtrim(config('elearning.digitalocean_config.cdn_base_url', ''), '/')
        );
    }

    public function createBunnyDriver(): PresignedUrlGenerator
    {
        return new BunnyPresigner(
            (int) setting('video_bunny_library_id'),
            setting('video_bunny_api_key')
        );
    }

    public function createServerDriver(): PresignedUrlGenerator
    {
        return new ServerIntent(
            config('services.internal_upload.url', env('INTERNAL_UPLOAD_ENDPOINT', '')),
            env('INTERNAL_TOKEN', '')
        );
    }
}
