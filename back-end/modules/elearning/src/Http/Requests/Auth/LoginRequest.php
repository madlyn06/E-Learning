<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Auth;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class LoginRequest extends BaseCustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ];
    }
}
