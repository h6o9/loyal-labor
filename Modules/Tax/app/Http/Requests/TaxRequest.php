<?php

namespace Modules\Tax\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TaxRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'percentage' => 'required|numeric',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|string';
            $rules['slug'] = 'sometimes|string|max:255';
        }
        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => __('Title must be unique.'),
            'title.max' => __('Title must be string with a maximum length of 255 characters.'),
            'slug.required' => __('Slug is required.'),
            'slug.max' => __('Slug must be string with a maximum length of 255 characters.'),
            'percentage.required' => __('Percentage is required'),
        ];
    }
}
