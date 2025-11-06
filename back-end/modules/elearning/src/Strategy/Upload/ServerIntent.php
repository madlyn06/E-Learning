<?php

declare(strict_types=1);

namespace Modules\Elearning\Strategy\Upload;

use Modules\Elearning\Interfaces\PresignedUrlGenerator;

class ServerIntent implements PresignedUrlGenerator
{
    public function __construct(protected string $endpoint, protected string $token) {}

    public function presign(string $filename, string $contentType, ?int $size = null): array
    {
        return [
            'driver'  => 'server',
            'payload' => [
                'uploadUrl' => $this->endpoint,
                'headers'   => ['Authorization' => "Bearer {$this->token}"],
            ],
        ];
    }
}
