<?php

namespace App\Http\Requests\Blog;

use App\Http\Requests\BaseRequest;

class ListRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => ['required', 'integer'],
            'skip' => ['required', 'integer'],
        ];
    }
}
