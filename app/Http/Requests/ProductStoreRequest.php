<?php

namespace App\Http\Requests;

use App\Rules\StartsWithSku;
use App\Rules\TwoDecimalPlaces;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ProductStoreRequest
 *
 * Handles validation for creating a new product.
 * Applies custom formatting and ensures the input follows business rules,
 * including SKU formatting and price precision.
 *
 * @package App\Http\Requests
 */
class ProductStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:40', 'unique:products,sku', new StartsWithSku()],
            'price' => ['required', 'numeric', 'min:0', new TwoDecimalPlaces()],
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Automatically trims and formats the SKU and name fields before validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'sku' => trim(strtoupper($this->input('sku'))),
            'name' => trim(strtolower($this->input('name'))),
        ]);
    }
}
