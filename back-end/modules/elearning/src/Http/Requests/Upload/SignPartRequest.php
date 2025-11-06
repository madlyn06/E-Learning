<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Upload;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class SignPartRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'driver' => 'required|in:s3,digitalocean',
            'key' => 'required|string',
            'uploadId' => 'required|string',
            'partNumber' => 'required|integer|min:1',
        ];
    }
}
