<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;

class RefeshTokenRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['required']
        ];
    }
}
