<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Auth;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class ResetPasswordRequest extends BaseCustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:elearning__users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
