<?php

namespace Modules\Product\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductLabelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ];

        if ($this->method() == 'PUT' && request()->get('code') == allLanguages()->first()->code) {
            $rules['slug'] = 'required|string|max:255|unique:product_labels,slug,'.$this->route('label');
        } elseif ($this->method() == 'POST') {
            $rules['slug'] = [
                'required',
                'string',
                'max:255',
            ];

            $rules['status'] = 'required|in:1,0';
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('Name is required'),
            'slug.required' => __('Slug is required'),
            'status.required' => __('Status is required'),
            'status.in' => __('Status must be Active or Inactive'),
        ];
    }
}
