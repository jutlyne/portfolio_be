<?php

namespace App\Http\Requests\Blog;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Str;

class CreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string'],
            'image' => ['required', 'file', 'max:2048', 'mimetypes:image/*'],
            'short_text' => ['required', 'string', 'max:200'],
            'tags' => ['required'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug($this->slug, '-')]);
    }
}
