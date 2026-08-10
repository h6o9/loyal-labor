<?php

namespace Modules\Blog\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'blog_category_id' => 'sometimes|exists:blog_categories,id',
            'seo_title' => 'nullable|string|max:1000',
            'seo_description' => 'nullable|string|max:2000',
            'tags' => 'nullable|string|max:2000',
            'show_homepage' => 'nullable',
            'is_popular' => 'nullable',
            'status' => 'nullable',
            'description' => 'required',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['title'] = 'required|string|max:255';
            $rules['slug'] = 'sometimes|string|max:255|unique:blogs,slug,'.$this->blog;
            $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
            $rules['slug'] = 'required|string|max:255|unique:blogs,slug';
            $rules['title'] = 'required|string|max:255|unique:blog_translations,title';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'blog_category_id.required' => __('The category is required.'),
            'blog_category_id.exists' => __('The selected category is invalid.'),

            'code.required' => __('Language is required and must be a string.'),
            'code.exists' => __('The selected language is invalid.'),

            'tags.max' => __('Tags may not be greater than 255 characters.'),
            'tags.string' => __('Tags must be a string.'),

            'image.required' => __('Image is required'),
            'image.image' => __('The image must be an image.'),
            'image.max' => __('The image may not be greater than 2048 kilobytes.'),

            'slug.required' => __('Slug is required.'),
            'slug.string' => __('The slug must be a string.'),
            'slug.max' => __('The slug may not be greater than 255 characters.'),
            'slug.unique' => __('The slug has already been taken.'),

            'title.required' => __('The title is required.'),
            'title.string' => __('The title must be a string.'),
            'title.max' => __('The title may not be greater than 255 characters.'),
            'title.unique' => __('Title must be unique.'),

            'description.required' => __('Description is required.'),

            'seo_title.max' => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string' => __('SEO title must be a string.'),
            'seo_description.max' => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string' => __('SEO description must be a string.'),
        ];
    }
}
