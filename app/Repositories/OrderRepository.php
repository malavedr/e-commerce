<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data, array $products): Order
    {
        // try {
        //     return Product::create($data);
        // } catch (\Throwable $e) {
        //     throw new ProductCreationFailedException([
        //         'exception' => $e->getMessage(),
        //     ]);
        // }

        // dump($products);

        $order = Order::create($data);

        foreach ($products as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            $quantity = $productData['quantity'];
            $unitPrice = $product->price;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $quantity * $unitPrice,
            ]);
        }

        return $order->fresh(['items.product', 'deliveryAddress']);
    }

    public function findByIdWithRelations(int|string $id): ?Order
    {
        return Order::with(['items.product', 'deliveryAddress'])->findOrFail($id);
    }
}