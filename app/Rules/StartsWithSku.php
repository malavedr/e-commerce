<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validation rule to ensure a string starts with the prefix "SKU-".
 */
class StartsWithSku implements ValidationRule
{
    /**
     * Validate that the given attribute's value starts with "SKU-".
     *
     * @param string $attribute The attribute name being validated.
     * @param mixed $value The value of the attribute.
     * @param Closure $fail Callback to call when validation fails.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! str_starts_with($value, 'SKU-')) {
            $fail(__('validation.starts_with_sku', ['attribute' => $attribute]));
        }
    }
}
