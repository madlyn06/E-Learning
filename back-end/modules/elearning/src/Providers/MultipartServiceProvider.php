<?php

declare(strict_types=1);

namespace Modules\Elearning\Providers;

use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;
use Modules\Elearning\Services\MultipartStorageService;

class MultipartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // S3
        $this->app->singleton('multipart.s3', function () {
            $s3 = new S3Client([
                'version' => '2006-03-01',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
            return new MultipartStorageService($s3, env('AWS_BUCKET'), rtrim(env('AWS_CDN_BASE_URL',''), '/'));
        });

        // DigitalOcean Spaces
        $this->app->singleton('multipart.spaces', function () {
            $s3 = new S3Client([
                'version' => '2006-03-01',
                'region' => env('SPACES_REGION'),
                'endpoint' => rtrim(env('SPACES_ENDPOINT'), '/'),
                'use_path_style_endpoint' => false,
                'credentials' => [
                    'key' => env('SPACES_KEY'),
                    'secret' => env('SPACES_SECRET'),
                ],
            ]);
            return new MultipartStorageService($s3, env('SPACES_BUCKET'), rtrim(env('SPACES_CDN_BASE_URL',''), '/'));
        });
    }
}
