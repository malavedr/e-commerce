<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure a numeric value has at most two decimal places.
 */
class TwoDecimalPlaces implements ValidationRule
{
        /**
     * Validate that the given attribute's value has up to two decimal places.
     *
     * @param string $attribute The name of the attribute under validation.
     * @param mixed $value The value of the attribute.
     * @param Closure $fail Callback to invoke if validation fails.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d+(\.\d{1,2})?$/', (string) $value)) {
            $fail(__('validation.two_decimal_places', ['attribute' => $attribute]));
        }
    }
}
