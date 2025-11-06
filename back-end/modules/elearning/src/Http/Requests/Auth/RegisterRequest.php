<?php

declare(strict_types=1);

namespace Modules\Elearning\Http\Requests\Auth;

use Modules\Elearning\Http\Requests\BaseCustomRequest;

class RegisterRequest extends BaseCustomRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:elearning__users',
            'password' => 'required|string|min:6',
        ];
    }
}
