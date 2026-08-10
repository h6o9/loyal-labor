<?php

namespace Modules\PageBuilder\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required',
        ];

        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|max:255|unique:customizeable_pages,slug';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => __('The title field is required.'),
            'title.string' => __('The title must be a string.'),
            'title.max' => __('The title may not be greater than 255 characters.'),
            'description.required' => __('Description is required.'),
            'description.string' => __('The description must be a string.'),
            'slug.required' => __('Slug is required.'),
            'slug.string' => __('The slug must be a string.'),
            'slug.max' => __('The slug may not be greater than 255 characters.'),
            'slug.unique' => __('The slug has already been taken.'),
        ];
    }
}
