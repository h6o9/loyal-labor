<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email,' . auth('web')->id(),
            'phone'      => 'required|string|max:20',
            'bio'        => 'nullable|string|max:2000',
            'birthday'   => 'nullable|date',
            'gender'     => 'required|in:male,female,other',
            'image'      => 'nullable|image|mimetypes:image/jpeg,image/png,image/gif,image/webp|max:512',
            'zip_code'   => 'required',
            'address'    => 'required',
            'country_id' => 'required|exists:countries,id',
            'state_id'   => 'required|exists:states,id',
            'city_id'    => 'required|exists:cities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => __('Name is required'),
            'name.string'         => __('Name must be a string'),
            'name.max'            => __('Name must be less than 50 characters'),

            'email.required'      => __('Email is required'),
            'email.email'         => __('Email is not valid'),
            'email.unique'        => __('Email is already in use'),

            'phone.required'      => __('Phone is required'),
            'phone.string'        => __('Phone must be a number'),
            'phone.max'           => __('Phone must be less than 20 characters'),

            'bio.string'          => __('Bio must be a string'),
            'bio.max'             => __('Bio must be less than 2000 characters'),

            'birthday.date'       => __('Birthday must be a valid date'),

            'gender.required'     => __('Gender is required'),
            'gender.in'           => __('Gender must be male, female, or other'),

            'zip_code.required'   => __('Zip code is required'),
            'address.required'    => __('Address is required'),

            'country_id.required' => __('Country is required'),
            'country_id.exists'   => __('Country not found'),

            'state_id.required'   => __('State is required'),
            'state_id.exists'     => __('State not found'),

            'city_id.required'    => __('City is required'),
            'city_id.exists'      => __('City not found'),

            'image.image'         => __('Image must be an image'),
            'image.mimetypes'     => __('Image must be a jpeg, png, gif, or webp'),
            'image.max'           => __('Image must be less than 512kb'),
        ];
    }
}
