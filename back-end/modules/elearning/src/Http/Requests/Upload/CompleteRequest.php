<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Upload;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class CompleteRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'driver' => 'required|in:s3,digitalocean',
            'key' => 'required|string',
            'uploadId' => 'required|string',
            'parts' => 'required|array|min:1',
            'parts.*.partNumber' => 'required|integer|min:1',
            'parts.*.etag' => 'required|string',
        ];
    }
}
