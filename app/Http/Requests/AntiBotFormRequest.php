<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AntiBotFormRequest extends FormRequest
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
            //
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $formStart = (int) $this->input('form_start_time');
            $timeDiff = now()->getTimestampMs() - $formStart;

            if ($timeDiff < 1000) {
                $validator->errors()->add('form', 'Bot detected: Submitted too quickly.');
            }

            if ($this->input('js_enabled') !== 'true') {
                $validator->errors()->add('form', 'Bot detected: JavaScript not enabled.');
            }

            if (!empty($this->input('website'))) {
                $validator->errors()->add('form', 'Bot detected: Honeypot triggered.');
            }
        });
    }
}
