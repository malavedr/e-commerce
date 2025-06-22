<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TwoDecimalPlaces;
use App\Rules\StartsWithSku;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('sku')) {
            $this->merge(['sku' => trim(strtoupper($this->input('sku')))]);
        }

        if ($this->has('name')) {
            $this->merge(['name' => trim(strtolower($this->input('name')))]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'sku' => [
                'sometimes',
                'string',
                'max:40',
                Rule::unique('products', 'sku')->ignore($this->route('product')),
                new StartsWithSku(),
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
                new TwoDecimalPlaces(),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
