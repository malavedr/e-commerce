<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orders
    ) {}

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

    public function orderDetails(Order $order): Order
    {
        return $order->load([
            'items.product',
            'deliveryAddress',
        ]);
    }
}
