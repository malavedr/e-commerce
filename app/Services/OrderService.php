<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;

/**
 * Class OrderService
 *
 * Handles the business logic for placing and preparing orders.
 *
 * @package App\Services
 */
class OrderService
{
    /**
     * OrderService constructor.
     *
     * @param  \App\Repositories\Contracts\OrderRepositoryInterface  $orders
     */
    public function __construct(
        protected OrderRepositoryInterface $orders
    ) {}

    /**
     * Place a new order with given data and product details.
     *
     * Retrieves the authenticated user, fetches their active delivery address,
     * prepares order items and totals, and stores the order using the repository.
     *
     * @param  array  $baseData  Base order information such as notes and status.
     * @param  array  $productData  Associative array of SKUs and quantities.
     * @return \App\Models\Order
     */
    public function placeOrder(array $baseData, array $productData): Order
    {
        $user = auth()->user();
        $deliveryAddress = $user->activeDeliveryAddress();

        $prepared = $this->prepareItemsAndTotals($productData);

        $order = $this->orders->createOrder([
            ...$baseData,
            'buyer_id' => $user->id,
            'delivery_address_id' => $deliveryAddress->id,
            ...$prepared['totals']
        ], $prepared['items']);

        return $order;
    }

    /**
     * Prepare order items and calculate totals.
     *
     * Iterates over the product data, fetches prices from the database,
     * and calculates subtotal, discount, tax, and total.
     *
     * @param  array  $products  Associative array of SKUs => quantity.
     * @return array{
     *     items: array<array{
     *         product_id: int,
     *         quantity: int,
     *         unit_price: float,
     *         total_price: float
     *     }>,
     *     totals: array{
     *         sub_total: float,
     *         discount_total: float,
     *         tax_total: float,
     *         total: float
     *     }
     * }
     */
    protected function prepareItemsAndTotals(array $products): array
    {
        $items = [];
        $subTotal = 0;

        foreach ($products as $product_sku => $quantity) {
            $product = Product::where('sku', $product_sku)->firstOrFail();
            $unitPrice = $product->price;
            $totalPrice = $quantity * $unitPrice;

            $items[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ];

            $subTotal += $totalPrice;
        }

        $discountTotal = 0;
        $taxTotal = 0;
        $total = $subTotal - $discountTotal + $taxTotal;

        return [
            'items' => $items,
            'totals' => [
                'sub_total' => $subTotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total' => $total,
            ],
        ];
    }
}
