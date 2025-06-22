<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TwoDecimalPlaces implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\d+(\.\d{1,2})?$/', (string) $value)) {
            $fail(__('validation.two_decimal_places', ['attribute' => $attribute]));
        }
    }
}
