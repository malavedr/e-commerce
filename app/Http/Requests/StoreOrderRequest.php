<?php

namespace App\Http\Requests;

use App\Enums\OrderStatusEnum;
use App\Models\Product;
use App\Rules\StartsWithSku;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Redis;

/**
 * Class StoreOrderRequest
 *
 * Handles validation and authorization for placing a new order.
 * Validates notes and products data, including SKU format, existence, quantity, 
 * and checks for active delivery address and duplicate orders.
 *
 * @package App\Http\Requests
 */
class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always returns true, authorization handled elsewhere.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array Validation rules for notes and products with nested SKU and quantity constraints.
     */
    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.sku' => ['required', 'distinct', 'exists:products,sku', new StartsWithSku()],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Prepare data for validation.
     * Trims and uppercases SKU input.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'sku' => trim(strtoupper($this->input('sku'))),
        ]);
    }

    /**
     * Configure the validator instance with additional custom validations.
     * Checks for an active delivery address, product activity status,
     * and prevents duplicate orders by locking using Redis.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $hasActiveAddress = $user->deliveryAddresses()->active()->exists();

            if (!$hasActiveAddress) {
                $validator->errors()->add('delivery_address', __('order.errors.no_active_delivery_address'));
            }
           
            $products = $this->input('products', []);

            foreach ($products as $item) {
                $skuId = $item['sku'] ?? null;

                if (! $skuId) {
                    continue;
                }

                $isActive = Product::where('sku', $skuId)->active()->exists();

                if (!$isActive) {
                    $validator->errors()->add('products',__("product.validation.inactive", ['id' => $skuId]));
                }
            }

            $productSkus = collect($this->input('products'))->pluck('sku')->sort()->values()->toArray();
            $productIds = Product::whereIn('sku', $productSkus)->pluck('id')->sort()->values()->toArray();
            
            ksort($productIds);
            $key = "orders:lock:$user->id:" . md5(json_encode($productIds));
            $locked = Redis::get($key);

            if ($locked) {
                $validator->errors()->add('products', __('order.errors.duplicate_order'));
            } else {
                $hasDuplicate = $user->orders()
                    ->where('status', OrderStatusEnum::PENDING)
                    ->whereHas('items', function ($q) use ($productIds) {
                        $q->whereIn('product_id', $productIds);
                    })
                    ->get()
                    ->filter(function ($order) use ($productIds) {
                        $ids = $order->items->pluck('product_id')->sort()->values()->toArray();
                        return $ids === $productIds;
                    })
                    ->isNotEmpty();

                if ($hasDuplicate) {
                    Redis::set($key, 1, 'EX', 600);
                    $validator->errors()->add('products', __('order.errors.duplicate_order'));
                }
            }
        });
    }

    /**
     * Custom error messages for validation failures.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'products.required' => __('order.validation.products.required'),
            'products.*.sku.required' => __('order.validation.products.sku_required'),
            'products.*.sku.exists' => __('order.validation.products.sku_not_found'),
            'products.*.sku.distinct' => __('order.validation.products.sku_distinct'),
            'products.*.quantity.required' => __('order.validation.products.quantity_required'),
            'products.*.quantity.min' => __('order.validation.products.quantity_min'),
        ];
    }
}
