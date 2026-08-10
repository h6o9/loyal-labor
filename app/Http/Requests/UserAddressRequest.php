<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAddressRequest extends FormRequest
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
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:25',
            'address'      => 'required|string|max:255',
            'zip'          => 'required|string|max:10',
            'a_country_id' => 'required|exists:countries,id',
            'a_state_id'   => 'required|exists:states,id',
            'a_city_id'    => 'required|exists:cities,id',
            'type'         => 'required|in:home,office',
            'is_default'   => 'sometimes|in:0,1',
            'status'       => 'sometimes|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => __('Name is required'),
            'email.required'        => __('Email is required'),
            'email.email'           => __('Email is not valid'),
            'phone.required'        => __('Phone is required'),
            'zip.required'          => __('Zip code is required'),

            'address.required'      => __('Address is required'),
            'address.required'      => __('Address is required'),

            'a_country_id.required' => __('Country is required'),
            'a_country_id.exists'   => __('Country not found'),

            'a_state_id.required'   => __('State is required'),
            'a_state_id.exists'     => __('State not found'),

            'a_city_id.required'    => __('City is required'),
            'a_city_id.exists'      => __('City not found'),

            'type.required'         => __('Type is required'),
            'type.in'               => __('Type is invalid'),

            'is_default.sometimes'  => __('Is default is required'),
            'is_default.in'         => __('Is default is invalid'),

            'status.sometimes'      => __('Status is required'),
            'status.in'             => __('Status is invalid'),
        ];
    }
}
