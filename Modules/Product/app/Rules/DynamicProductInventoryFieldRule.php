<?php

namespace Modules\Product\app\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DynamicProductInventoryFieldRule implements ValidationRule
{
    protected string $field;

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
            case 'manage_stock':
                if (! in_array($value, [0, 1])) {
                    $fail("The {$attribute} must be true or false.");
                }
                break;
            case 'stock_status':
                if (! in_array($value, ['in_stock', 'out_of_stock'])) {
                    $fail("The {$attribute} must be in stock or out of stock.");
                }
                break;

            case 'stock_qty':
                if (filter_var($value, FILTER_VALIDATE_INT) === false) {
                    $fail("The {$attribute} must be an integer.");
                    return;
                }

                // cannot be negative
                if ((int) $value < 0) {
                    $fail("The {$attribute} cannot be negative.");
                }
                break;
            default:
                $fail("Invalid field: {$this->field}");
        }
    }
}
