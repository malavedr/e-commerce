<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StartsWithSku implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! str_starts_with($value, 'SKU-')) {
            $fail(__('validation.starts_with_sku', ['attribute' => $attribute]));
        }
    }
}
