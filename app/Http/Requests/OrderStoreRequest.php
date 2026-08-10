<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
        return [
            'name'                => ['required', 'string', 'max:191'],
            'email'               => ['required', 'string', 'email', 'max:191'],
            'phone'               => ['required', 'string', 'max:255'],
            'zip'                 => ['required_unless:same_as_shipping,1', 'max:15'],
            'country_id'          => ['required_unless:same_as_shipping,1'],
            'state_id'            => ['required_unless:same_as_shipping,1'],
            'city_id'             => ['required_unless:same_as_shipping,1'],
            'address'             => ['required_unless:same_as_shipping,1', 'max:130'],
            'note'                => ['nullable', 'string', 'max:2000'],
            'shipping'            => ['required'],
            'payment_method'      => ['required'],
            'shipping_address_id' => 'sometimes|required|exists:addresses,id',
            'same_as_shipping'    => 'sometimes|in:0,1',
            'create_account'      => 'nullable',
            'account_password'    => 'required_if:create_account,1|nullable|string|min:4',
            'guest_checkout'      => 'sometimes',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                       => __('Name is required'),
            'email.required'                      => __('Email is required'),
            'email.string'                        => __('Email must be a string'),
            'email.email'                         => __('Email must be a valid email'),
            'email.max'                           => __('Email must be less than 191 characters'),
            'phone.required'                      => __('Phone is required'),
            'phone.string'                        => __('Phone must be a string'),
            'phone.max'                           => __('Phone must be less than 191 characters'),
            'zip.required_unless'                 => __('Zip code is required'),
            'zip.max'                             => __('Zip code must be less than 15 characters'),
            'country_id.required_unless'          => __('Country is required'),
            'state_id.required_unless'            => __('State is required'),
            'city_id.required_unless'             => __('City is required'),
            'address.required_unless'             => __('Address is required'),
            'note.required'                       => __('Note is required'),
            'note.string'                         => __('Note must be a string'),
            'note.max'                            => __('Note must be less than 2000 characters'),
            'shipping.required'                   => __('Shipping is required'),
            'payment_method.required'             => __('Payment method is required'),
            'shipping_address_id.required'        => __('Shipping address is required'),
            'shipping_address_id.required_unless' => __('Shipping address is required'),
            'shipping_address_id.exists'          => __('Shipping address does not exist'),
            'same_as_shipping.required'           => __('Same as shipping is required'),
            'same_as_shipping.in'                 => __('Same as shipping must be Check or Uncheck'),
            'create_account.required'             => __('Create account is required'),
            'account_password.required_if'        => __('Account password is required if create account is checked'),
            'account_password.min'                => __('Account password must be at least 4 characters'),
            'account_password.string'             => __('Account password must be a string'),
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'country' => \App\Models\Country::find($this->country_id)->name ?? null,
            'state'   => \App\Models\State::find($this->state_id)->name ?? null,
            'city'    => \App\Models\City::find($this->city_id)->name ?? null,
        ]);
    }
}
