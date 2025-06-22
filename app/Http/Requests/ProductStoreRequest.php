<?php

namespace App\Http\Requests;

use App\Rules\StartsWithSku;
use App\Rules\TwoDecimalPlaces;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'sku' => trim(strtoupper($this->input('sku'))),
            'name' => trim(strtolower($this->input('name'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:40', 'unique:products,sku', new StartsWithSku()],
            'price' => ['required', 'numeric', 'min:0', new TwoDecimalPlaces()],
            'description' => ['nullable', 'string'],
        ];
    }
}
