<?php

declare(strict_types=1);

namespace Modules\Elearning\Strategy\Upload;

use Modules\Elearning\Interfaces\PresignedUrlGenerator;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class DigitalOceanPresigner implements PresignedUrlGenerator
{
    public function __construct(protected S3Client $s3, protected string $bucket, protected string $cdnBaseUrl) {}

    public function presign(string $filename, string $contentType, ?int $size = null): array
    {
        $key = 'videos/' . now()->timestamp . '-' . Str::random(8) . '-' . $this->sanitize($filename);

        $cmd = $this->s3->getCommand('PutObject', [
            'Bucket'      => $this->bucket,
            'Key'         => $key,
            'ContentType' => $contentType,
        ]);

        $req = $this->s3->createPresignedRequest($cmd, '+5 minutes');

        return [
            'driver'  => 'digitalocean',
            'payload' => [
                'uploadUrl' => (string) $req->getUri(),
                'headers'   => ['Content-Type' => $contentType],
                'publicUrl' => rtrim($this->cdnBaseUrl, '/') . '/' . $key,
                'key'       => $key,
            ],
        ];
    }

    private function sanitize(string $name): string
    {
        $name = preg_replace('/[^A-Za-z0-9\.\-\_]/', '-', $name);
        return trim($name ?: 'file', '-');
    }
}
