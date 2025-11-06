<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Elearning\Http\Requests\Upload\AbortRequest;
use Modules\Elearning\Http\Requests\Upload\PresignUrlRequest;
use Modules\Elearning\Http\Requests\Upload\PreflightRequest;
use Modules\Elearning\Http\Requests\Upload\SignPartRequest;
use Modules\Elearning\Http\Requests\Upload\CompleteRequest;
use Modules\Elearning\Strategy\Upload\UploadManager;
use Illuminate\Support\Str;

class VideoController extends BaseController
{
    public function getAvailableUploaderDrivers(): JsonResponse
    {
        return $this->successResponse(['driver' => setting('video_provider')]);
    }

    public function preflight(PreflightRequest $request): JsonResponse
    {
        $data = $request->validated();
        $size = $data['size'] ?? (3 * 1024 * 1024);
        $type = $data['type'] ?? 'application/octet-stream';

        $svc = $data['driver'] === 's3' ? app('multipart.s3') : app('multipart.spaces');

        $ref = new \ReflectionClass($svc);
        $propS3 = $ref->getProperty('s3'); $propS3->setAccessible(true);
        $propBucket = $ref->getProperty('bucket'); $propBucket->setAccessible(true);
        $client = $propS3->getValue($svc);
        $bucket = $propBucket->getValue($svc);

        $key = 'preflight/'.now()->timestamp.'-'.Str::random(8).'.bin';
        $cmd = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $key,
            'ContentType' => $type,
            'ContentLength' => $size,
        ]);
        $url = (string) $client->createPresignedRequest($cmd, '+10 minutes')->getUri();

        return $this->successResponse([
            'driver' => $data['driver'],
            'uploadUrl' => $url,
            'headers' => ['Content-Type' => $type],
            'size' => $size,
            'key' => $key,
        ]);
    }

    public function signPart(SignPartRequest $request): JsonResponse
    {
        $data = $request->validated();
        $svc = $data['driver'] === 's3' ? app('multipart.s3') : app('multipart.spaces');
        $url = $svc->signPart($data['key'], $data['uploadId'], $data['partNumber'], 1800);
        return $this->successResponse(['uploadUrl' => $url]);
    }

    public function complete(CompleteRequest $request): JsonResponse
    {
        $data = $request->validated();
        $svc = $data['driver'] === 's3' ? app('multipart.s3') : app('multipart.spaces');
        return $this->successResponse($svc->complete($data['key'], $data['uploadId'], $data['parts']));
    }

    public function abort(AbortRequest $request): JsonResponse
    {
        $data = $request->validated();
        $svc = $data['driver'] === 's3' ? app('multipart.s3') : app('multipart.spaces');
        $svc->abort($data['key'], $data['uploadId']);
        return $this->successResponse(['ok'=>true]);
    }

    public function presignedUrl(PresignUrlRequest $request, UploadManager $manager): JsonResponse
    {
        $data = $request->validated();

        $driver = $manager->getDefaultDriver();
        $presigner = $manager->driver($driver);

        return $this->successResponse(
            $presigner->presign($data['filename'], $data['type'], (int) $data['size'] ?? null)
        );
    }
}
