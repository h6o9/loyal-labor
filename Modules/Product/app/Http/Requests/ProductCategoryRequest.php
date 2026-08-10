<?php

namespace Modules\Product\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryRequest extends FormRequest
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
            'name'            => [
                'required',
                'string',
                'max:255',
                Rule::unique('category_translations')->ignore($this->category, 'category_id')->where('lang_code', request('code', getSessionLanguage())),
            ],
            'slug'            => 'sometimes|required|unique:categories,slug,' . $this->category,
            'parent_id'       => 'nullable|exists:categories,id',
            'status'          => 'sometimes|required',
            'type'            => 'sometimes|required',
            'position'        => 'nullable|numeric',
            'is_searchable'   => 'nullable',
            'is_featured'     => 'nullable',
            'seo_title'       => 'nullable',
            'seo_description' => 'nullable',
        ];

        if ($this->method() == 'POST') {
            $rules['image'] = 'required|file|image';
            $rules['icon'] = 'required|file|image';
        }

        if ($this->method() == 'PUT') {
            $rules['image'] = 'nullable|file|image';
            $rules['icon'] = 'nullable|file|image';
            $rules['name']  = [
                'required',
                'string',
                'max:255',
            ];
        }

        return $rules;
    }
}
