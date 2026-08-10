<?php

namespace Modules\Blog\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    /**
     * @return mixed
     */
    public function rules(): array
    {
        $rules = [];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|string';

            $categoryId                 = $this->route('blog_category');
            $rules['title']             = 'required|string|max:255|unique:blog_category_translations,title,' . $categoryId?->id . ',blog_category_id';
            $rules['short_description'] = 'nullable|string|max:255';

        }
        if ($this->isMethod('post')) {
            $rules['title']             = 'required|string|max:255|unique:blog_category_translations,title';
            $rules['slug']              = 'required|string|max:255|unique:blog_categories,slug';
            $rules['short_description'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'code.required'  => __('Language is required and must be a string.'),
            'code.exists'    => __('The selected language is invalid.'),
            'title.required' => __('The title is required.'),
            'title.max'      => __('Title must be string with a maximum length of 255 characters.'),
            'title.unique'   => __('Title must be unique.'),
            'slug.required'  => __('Slug is required.'),
            'slug.string'    => __('The slug must be a string.'),
            'slug.max'       => __('The slug may not be greater than 255 characters.'),
            'slug.unique'    => __('The slug has already been taken.'),
        ];
    }
}
