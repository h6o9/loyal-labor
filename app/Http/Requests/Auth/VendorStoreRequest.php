<?php

namespace App\Http\Requests\Auth;

use App\Rules\CustomRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class VendorStoreRequest extends FormRequest
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
        $isAuth = auth()->check();

        return [
            'name'            => 'required|string|max:50',
            'shop_name'       => 'required|string|max:50',

            'email'           => $isAuth
            ? 'nullable|string|email|max:100|unique:users,email,' . auth()->id()
            : 'required|string|email|max:100|unique:users,email',

            'password'        => $isAuth
            ? ['nullable']
            : ['required', 'confirmed', 'min:4', 'max:100'],

            'phone'           => $isAuth ? 'nullable|string|max:20' : 'required|string|max:20',
            'bio'             => 'nullable|string|max:2000',
            'zip_code'        => $isAuth ? 'nullable|string|max:20' : 'required|string|max:20',
            'address'         => 'required|string|max:191',
            'country_id'      => $isAuth ? 'nullable|exists:countries,id' : 'required|exists:countries,id',
            'state_id'        => $isAuth ? 'nullable|exists:states,id' : 'required|exists:states,id',
            'city_id'         => $isAuth ? 'nullable|exists:cities,id' : 'required|exists:cities,id',

            'recaptcha_token' => getSettingStatus('recaptcha_status') ? ['required', new CustomRecaptcha] : '',
            'tos'             => ['required', 'accepted'],
            'kyc_type'        => ['required', 'exists:kyc_types,id'],
            'kyc_file'        => 'required|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'            => __('Name is required'),
            'name.max'                 => __('Name may not be greater than 50 characters'),

            'shop_name.required'       => __('Shop name is required'),
            'shop_name.max'            => __('Shop name may not be greater than 50 characters'),

            'email.required'           => __('Email is required'),
            'email.email'              => __('Please provide a valid email address'),
            'email.max'                => __('Email may not be greater than 100 characters'),
            'email.unique'             => __('Email already exists'),

            'password.required'        => __('Password is required'),
            'password.confirmed'       => __('Confirm password does not match'),
            'password.min'             => __('Password must be at least 4 characters'),
            'password.max'             => __('Password may not be greater than 100 characters'),

            'phone.required'           => __('Phone number is required'),
            'phone.max'                => __('Phone number may not be greater than 20 characters'),

            'bio.max'                  => __('Bio may not be greater than 2000 characters'),

            'zip_code.required'        => __('Zip code is required'),
            'zip_code.max'             => __('Zip code may not be greater than 20 characters'),

            'address.required'         => __('Address is required'),
            'address.max'              => __('Address may not be greater than 191 characters'),

            'country_id.required'      => __('Country is required'),
            'country_id.exists'        => __('Selected country is invalid'),

            'state_id.required'        => __('State is required'),
            'state_id.exists'          => __('Selected state is invalid'),

            'city_id.required'         => __('City is required'),
            'city_id.exists'           => __('Selected city is invalid'),

            'recaptcha_token.required' => __('Please complete the reCAPTCHA to submit the form'),

            'tos.required'             => __('You must accept the Terms of Service'),
            'tos.accepted'             => __('You must accept the Terms of Service'),

            'kyc_type.required'        => __('KYC type is required'),
            'kyc_type.exists'          => __('Selected KYC type is invalid'),

            'kyc_file.required'        => __('KYC file is required'),
            'kyc_file.mimes'           => __('KYC file must be an image (jpg, jpeg, png, webp, pdf)'),
            'kyc_file.max'             => __('KYC file may not be larger than 2MB'),
        ];
    }
}
