<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Upload;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class PresignUrlRequest extends BaseCustomRequest
{
    public function rules(): array
    {
        return [
            'filename' => 'required|string',
            'type'     => 'required|string',
            'size'     => 'nullable|integer',
        ];
    }
}
