<?php

namespace App\Http\Requests;

use App\Rules\StartsWithSku;
use App\Rules\TwoDecimalPlaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class ProductUpdateRequest
 *
 * Handles validation for updating an existing product.
 * Supports partial updates and applies formatting and business rules
 * for SKU, price, and other fields.
 *
 * @package App\Http\Requests
 */
class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Applies conditional rules depending on the presence of fields.
     *
     * @return array<string, mixed>
     */
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

    /**
     * Prepare the data for validation.
     *
     * Normalizes SKU and name fields before validation when present.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('sku')) {
            $this->merge(['sku' => trim(strtoupper($this->input('sku')))]);
        }

        if ($this->has('name')) {
            $this->merge(['name' => trim(strtolower($this->input('name')))]);
        }
    }
}
