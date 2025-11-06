<?php

declare(strict_types=1);

namespace Modules\Elearning\Services;

use Aws\S3\S3Client;

class MultipartStorageService
{
    public function __construct(
        protected S3Client $s3,
        protected string $bucket,
        protected string $cdnBaseUrl
    ) {}

    public function create(string $key, string $contentType): array
    {
        $res = $this->s3->createMultipartUpload([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'ContentType' => $contentType,
        ]);
        return [
            'uploadId' => $res['UploadId'],
            'key' => $key,
            'bucket' => $this->bucket,
            'publicUrl' => rtrim($this->cdnBaseUrl, '/') . '/' . $key,
        ];
    }

    public function signPart(string $key, string $uploadId, int $partNumber, int $expiresIn = 1800): string
    {
        $cmd = $this->s3->getCommand('UploadPart', [
            'Bucket' => $this->bucket,
            'Key' => $key,
            'UploadId' => $uploadId,
            'PartNumber' => $partNumber,
        ]);
        $req = $this->s3->createPresignedRequest($cmd, "+{$expiresIn} seconds");
        return (string) $req->getUri();
    }

    public function complete(string $key, string $uploadId, array $parts): array
    {
        $res = $this->s3->completeMultipartUpload([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'UploadId' => $uploadId,
            'MultipartUpload' => ['Parts' => $parts],
        ]);
        return [
            'location' => (string) ($res['Location'] ?? (rtrim($this->cdnBaseUrl, '/') . '/' . $key)),
            'key' => $key,
        ];
    }

    public function abort(string $key, string $uploadId): void
    {
        $this->s3->abortMultipartUpload([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'UploadId' => $uploadId,
        ]);
    }
}
