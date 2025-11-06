<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Upload;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class PreflightRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'driver' => 'required|in:s3,digitalocean',
            'size' => 'nullable|integer',
            'type' => 'nullable|string',
        ];
    }
}
