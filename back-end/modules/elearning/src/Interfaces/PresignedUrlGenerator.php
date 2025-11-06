<?php

namespace Modules\Elearning\Interfaces;

interface PresignedUrlGenerator
{
    /**
     * Return { driver, payload } for client upload directly.
     *
     * @param  string $filename
     * @param  string $contentType
     * @param  int|null $size
     * @return array
     */
    public function presign(string $filename, string $contentType, ?int $size = null): array;
}
