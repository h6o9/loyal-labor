<?php

namespace Modules\Product\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DynamicProductPriceFieldRule implements ValidationRule
{
    protected string $field;

    /**
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        switch ($this->field) {
            case 'price':
            case 'offer_price':
                if (!is_numeric($value)) {
                    $fail("The {$attribute} must be a numeric value.");
                }
                break;

            case 'offer_price_start':
            case 'offer_price_end':
                if ($value && !$this->isValidDate($value)) {
                    $fail("The {$attribute} must be a valid date.");
                }
                break;
            case 'offer_price_type':
                if (!in_array($value, ['fixed', 'percentage'])) {
                    $fail("The {$attribute} must be fixed or percentage.");
                }
                break;
            default:
                $fail("Invalid field: {$this->field}");
        }
    }

    /**
     * Check if the date is valid (Y-m-d format or any strtotime-parsable format).
     */
    protected function isValidDate($value): bool
    {
        return strtotime($value) !== false;
    }
}
