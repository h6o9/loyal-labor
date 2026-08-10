<?php

namespace Modules\Testimonial\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (Auth::guard('admin')->check() && checkAdminHasPermission('testimonial.store')) ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'comment' => 'required|string|max:5000',
            'rating' => 'required|numeric|min:1|max:5',
        ];

        if ($this->isMethod('put')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name must be a string.'),
            'name.max' => __('The name may not be greater than 255 characters.'),
            'designation.required' => __('The designation field is required.'),
            'designation.string' => __('The designation must be a string.'),
            'designation.max' => __('The designation may not be greater than 255 characters.'),
            'comment.required' => __('The comment field is required.'),
            'comment.string' => __('The comment must be a string.'),
            'comment.max' => __('The comment may not be greater than 5000 characters.'),

            'rating.required' => __('The rating field is required.'),
            'rating.numeric' => __('The rating field must be a number.'),
            'rating.min' => __('The rating field must be at least 1.'),
            'rating.max' => __('The rating field must not be greater than 5.'),

            'image.required' => __('The image is required.'),
            'image.image' => __('The image must be an image.'),
            'image.max' => __('The image may not be greater than 2048 kilobytes.'),
        ];
    }
}
