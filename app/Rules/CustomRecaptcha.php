<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;
use ReCaptcha\ReCaptcha;

class CustomRecaptcha implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param string $action    Expected reCAPTCHA action (e.g., 'login', 'register')
     * @param float  $threshold Score threshold to consider as human
     */
    public function __construct(
        protected string $action = 'submit',
        protected float $threshold = 0.5
    ) {}

    /**
     * Run the validation rule.
     *
     * @param string            $attribute
     * @param mixed             $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $setting = Cache::get('setting');

        $recaptcha = new ReCaptcha($setting->recaptcha_secret_key);
        $response  = $recaptcha->verify($value, $_SERVER['REMOTE_ADDR']);

        if (!$response->isSuccess()) {
            $fail(__('Please complete the reCAPTCHA to submit the form'));
        }
    }
}
