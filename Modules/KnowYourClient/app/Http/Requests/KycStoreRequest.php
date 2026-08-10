<?php

namespace Modules\KnowYourClient\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file'        => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'kyc_type_id' => 'required|exists:kyc_types,id',
            'message'     => 'nullable|string|max:190',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'file.required'        => __('The file is required.'),
            'file.file'            => __('The file must be a valid file.'),
            'file.mimes'           => __('The file must be a file of type: jpg, jpeg, png, pdf, doc, docx.'),
            'file.max'             => __('The file may not be greater than 2MB.'),
            'kyc_type_id.required' => __('The KYC type is required.'),
            'kyc_type_id.exists'   => __('The selected KYC type is invalid.'),
            'message.string'       => __('The message must be a string.'),
            'message.max'          => __('The message may not be greater than 190 characters.'),
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
