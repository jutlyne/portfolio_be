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
        $rules = [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['required', 'string', 'max:200'],
            'read_minutes' => ['required', 'integer', 'min:0', 'max:60'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'file', 'max:2048', 'mimetypes:image/*'],
            'short_text' => ['required', 'string', 'max:200'],
            'tags' => ['required'],
            'tags.*' => ['integer', 'exists:tags,id'],
            'headings' => ['required', 'array'],
            'headings.*.key' => ['required', 'integer'],
            'headings.*.title' => ['required', 'string', 'max:255'],
            'headings.*.href' => ['required', 'string', 'max:255'],
            'headings.*.children' => ['nullable', 'array'],
            'headings.*.children.*.key' => ['required', 'integer'],
            'headings.*.children.*.title' => ['required', 'string', 'max:255'],
            'headings.*.children.*.href' => ['required', 'string', 'max:255'],
        ];

        if (request()->isMethod('post')) {
            $rules['image'] = ['required', 'file', 'max:2048', 'mimetypes:image/*'];
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['slug' => Str::slug($this->title, '-') . '-' . time()]);
    }
}
