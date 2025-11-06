<?php

declare(strict_types=1);

namespace Modules\Elearning\Strategy\Upload;

use Modules\Elearning\Interfaces\PresignedUrlGenerator;
use Illuminate\Support\Facades\Http;

class BunnyPresigner implements PresignedUrlGenerator
{
    public function __construct(protected int $libraryId, protected string $apiKey) {}

    public function presign(string $filename, string $contentType, ?int $size = null): array
    {
        $create = Http::withHeaders([
            'AccessKey'    => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("https://video.bunnycdn.com/library/{$this->libraryId}/videos", [
            'title' => $filename,
        ]);

        if (!$create->ok()) {
            throw new \RuntimeException("Bunny create video failed: {$create->status()} {$create->body()}");
        }

        $guid   = $create->json('guid');
        $expire = time() + 15 * 60; // 15 minutes
        $sig    = hash('sha256', "{$this->libraryId}{$this->apiKey}{$expire}{$guid}");

        return [
            'driver'  => 'bunny',
            'payload' => [
                'uploadUrl' => 'https://video.bunnycdn.com/tusupload',
                'headers'   => [
                    'AuthorizationSignature' => $sig,
                    'AuthorizationExpire'    => $expire,
                    'LibraryId'              => $this->libraryId,
                    'VideoId'                => $guid,
                ],
                'videoId' => $guid,
            ],
        ];
    }
}
